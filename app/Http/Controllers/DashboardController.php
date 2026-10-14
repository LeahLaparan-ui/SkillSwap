<?php

namespace App\Http\Controllers;

use App\Models\SwapRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the signed-in student's dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user()->load(['teachSkills', 'learnSkills']);

        $incoming = $user->receivedSwapRequests()
            ->where('status', SwapRequest::STATUS_PENDING)
            ->with(['sender', 'offeredSkill', 'requestedSkill'])
            ->latest()
            ->get();

        $outgoing = $user->sentSwapRequests()
            ->where('status', SwapRequest::STATUS_PENDING)
            ->with(['receiver', 'offeredSkill', 'requestedSkill'])
            ->latest()
            ->get();

        return view('dashboard', [
            'user' => $user,
            'incoming' => $incoming,
            'outgoing' => $outgoing,
            'matches' => $this->suggestedMatches($user),
        ]);
    }

    /**
     * Find students who teach a skill you want to learn and want to learn a skill you teach.
     *
     * @return Collection<int, User>
     */
    private function suggestedMatches(User $user): Collection
    {
        $learnIds = $user->learnSkills->pluck('id');
        $teachIds = $user->teachSkills->pluck('id');

        if ($learnIds->isEmpty() || $teachIds->isEmpty()) {
            return collect();
        }

        return User::query()
            ->where('id', '!=', $user->id)
            ->whereHas('teachSkills', fn ($query) => $query->whereIn('skills.id', $learnIds))
            ->whereHas('learnSkills', fn ($query) => $query->whereIn('skills.id', $teachIds))
            ->with(['teachSkills', 'learnSkills'])
            ->limit(6)
            ->get();
    }
}
