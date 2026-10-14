<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\User;
use App\Models\UserSkill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SkillController extends Controller
{
    /**
     * Browse and search students by skill.
     */
    public function index(Request $request): View
    {
        $search = $request->string('q')->trim()->value();
        $skillSlug = $request->string('skill')->trim()->value();
        $type = $request->string('type')->value();
        $type = in_array($type, ['teach', 'learn'], true) ? $type : 'teach';

        $students = User::query()
            ->with(['teachSkills', 'learnSkills'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('university', 'like', "%{$search}%");
                });
            })
            ->when($skillSlug, function ($query) use ($skillSlug, $type) {
                $relation = $type === 'learn' ? 'learnSkills' : 'teachSkills';
                $query->whereHas($relation, fn ($inner) => $inner->where('slug', $skillSlug));
            })
            ->where('id', '!=', $request->user()->id)
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('skills.index', [
            'students' => $students,
            'skills' => Skill::orderBy('name')->get(),
            'search' => $search,
            'skillSlug' => $skillSlug,
            'type' => $type,
        ]);
    }

    /**
     * Add a teach/learn skill to the signed-in student.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', Rule::in(['teach', 'learn'])],
            'proficiency' => ['nullable', Rule::in(['beginner', 'intermediate', 'advanced'])],
        ]);

        $skill = Skill::findOrCreateByName($data['name']);
        $user = $request->user();

        $alreadyLinked = $user->userSkills()
            ->where('skill_id', $skill->id)
            ->where('type', $data['type'])
            ->exists();

        if (! $alreadyLinked) {
            $user->userSkills()->create([
                'skill_id' => $skill->id,
                'type' => $data['type'],
                'proficiency' => $data['type'] === 'teach' ? ($data['proficiency'] ?? null) : null,
            ]);
        }

        return back()->with('status', 'skill-added');
    }

    /**
     * Remove a skill from the signed-in student.
     */
    public function destroy(Request $request, UserSkill $userSkill): RedirectResponse
    {
        abort_unless($userSkill->user_id === $request->user()->id, 403);

        $userSkill->delete();

        return back()->with('status', 'skill-removed');
    }
}
