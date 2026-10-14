<?php

namespace App\Http\Controllers;

use App\Models\SwapRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SwapRequestController extends Controller
{
    /**
     * List incoming and outgoing swap requests.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $incoming = $user->receivedSwapRequests()
            ->with(['sender', 'offeredSkill', 'requestedSkill'])
            ->latest()
            ->get();

        $outgoing = $user->sentSwapRequests()
            ->with(['receiver', 'offeredSkill', 'requestedSkill'])
            ->latest()
            ->get();

        return view('swaps.index', compact('incoming', 'outgoing'));
    }

    /**
     * Show the form to propose a swap to a student.
     */
    public function create(Request $request, User $user): View
    {
        abort_if($user->id === $request->user()->id, 404);

        return view('swaps.create', [
            'receiver' => $user->load(['teachSkills', 'learnSkills']),
            'myTeachSkills' => $request->user()->teachSkills()->orderBy('name')->get(),
            'theirTeachSkills' => $user->teachSkills()->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a new swap proposal.
     */
    public function store(Request $request, User $user): RedirectResponse
    {
        abort_if($user->id === $request->user()->id, 404);

        $data = $request->validate([
            'offered_skill_id' => ['required', 'integer'],
            'requested_skill_id' => ['required', 'integer'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $myTeachIds = $request->user()->teachSkills()->pluck('skills.id');
        $theirTeachIds = $user->teachSkills()->pluck('skills.id');

        if (! $myTeachIds->contains((int) $data['offered_skill_id'])) {
            throw ValidationException::withMessages([
                'offered_skill_id' => 'You can only offer a skill you are teaching.',
            ]);
        }

        if (! $theirTeachIds->contains((int) $data['requested_skill_id'])) {
            throw ValidationException::withMessages([
                'requested_skill_id' => 'That student does not teach the requested skill.',
            ]);
        }

        $hasPending = SwapRequest::query()
            ->where('status', SwapRequest::STATUS_PENDING)
            ->where(function ($query) use ($request, $user) {
                $query->where('sender_id', $request->user()->id)->where('receiver_id', $user->id);
            })
            ->orWhere(function ($query) use ($request, $user) {
                $query->where('sender_id', $user->id)->where('receiver_id', $request->user()->id);
            })
            ->exists();

        if ($hasPending) {
            return back()
                ->withErrors(['offered_skill_id' => 'You already have a pending swap with this student.'])
                ->withInput();
        }

        SwapRequest::create([
            'sender_id' => $request->user()->id,
            'receiver_id' => $user->id,
            'offered_skill_id' => $data['offered_skill_id'],
            'requested_skill_id' => $data['requested_skill_id'],
            'message' => $data['message'] ?? null,
            'status' => SwapRequest::STATUS_PENDING,
        ]);

        return redirect()->route('swaps.index')->with('status', 'swap-requested');
    }

    /**
     * Accept, decline, cancel, or complete a swap request.
     */
    public function update(Request $request, SwapRequest $swapRequest): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', Rule::in(['accept', 'decline', 'cancel', 'complete'])],
        ]);

        $user = $request->user();
        $isSender = $swapRequest->sender_id === $user->id;
        $isReceiver = $swapRequest->receiver_id === $user->id;

        abort_unless($isSender || $isReceiver, 403);

        switch ($data['action']) {
            case 'accept':
                abort_unless($isReceiver && $swapRequest->isPending(), 403);
                $swapRequest->update(['status' => SwapRequest::STATUS_ACCEPTED]);
                break;
            case 'decline':
                abort_unless($isReceiver && $swapRequest->isPending(), 403);
                $swapRequest->update(['status' => SwapRequest::STATUS_DECLINED]);
                break;
            case 'cancel':
                abort_unless($isSender && $swapRequest->isPending(), 403);
                $swapRequest->update(['status' => SwapRequest::STATUS_CANCELLED]);
                break;
            case 'complete':
                abort_unless($swapRequest->status === SwapRequest::STATUS_ACCEPTED, 403);
                $swapRequest->update(['status' => SwapRequest::STATUS_COMPLETED]);
                break;
        }

        return back()->with('status', 'swap-updated');
    }
}
