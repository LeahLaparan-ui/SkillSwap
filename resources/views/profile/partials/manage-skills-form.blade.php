<section>
    <header>
        <h2 class="text-lg font-medium text-beige-900">
            {{ __('Skills') }}
        </h2>

        <p class="mt-1 text-sm text-beige-600">
            {{ __('Add the skills you can teach and the skills you want to learn. Start typing to reuse an existing skill.') }}
        </p>
    </header>

    {{-- Current skills --}}
    <div class="mt-6 grid gap-6 sm:grid-cols-2">
        <div>
            <h3 class="text-xs font-semibold uppercase tracking-wide text-beige-500">Teaching</h3>
            <ul class="mt-3 space-y-2">
                @forelse ($user->userSkills as $userSkill)
                    @if ($userSkill->type === 'teach')
                        <li class="flex items-center justify-between rounded-md bg-sage-50 px-3 py-2">
                            <span class="text-sm font-medium text-sage-800">
                                {{ $userSkill->skill->name }}
                                @if ($userSkill->proficiency)
                                    <span class="text-xs font-normal text-sage-600">({{ $userSkill->proficiency }})</span>
                                @endif
                            </span>
                            <form method="POST" action="{{ route('skills.destroy', $userSkill) }}">
                                @csrf
                                @method('delete')
                                <button class="text-xs font-medium text-sage-700 hover:text-sage-900">Remove</button>
                            </form>
                        </li>
                    @endif
                @empty
                @endforelse
            </ul>
            @if ($user->userSkills->where('type', 'teach')->isEmpty())
                <p class="mt-2 text-sm text-beige-400">No teaching skills yet.</p>
            @endif
        </div>

        <div>
            <h3 class="text-xs font-semibold uppercase tracking-wide text-beige-500">Learning</h3>
            <ul class="mt-3 space-y-2">
                @foreach ($user->userSkills as $userSkill)
                    @if ($userSkill->type === 'learn')
                        <li class="flex items-center justify-between rounded-md bg-sage-50 px-3 py-2">
                            <span class="text-sm font-medium text-sage-800">{{ $userSkill->skill->name }}</span>
                            <form method="POST" action="{{ route('skills.destroy', $userSkill) }}">
                                @csrf
                                @method('delete')
                                <button class="text-xs font-medium text-sage-700 hover:text-sage-900">Remove</button>
                            </form>
                        </li>
                    @endif
                @endforeach
            </ul>
            @if ($user->userSkills->where('type', 'learn')->isEmpty())
                <p class="mt-2 text-sm text-beige-400">No learning goals yet.</p>
            @endif
        </div>
    </div>

    {{-- Add skill --}}
    <form method="POST" action="{{ route('skills.store') }}" class="mt-8 space-y-4">
        @csrf

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="sm:col-span-2">
                <x-input-label for="skill_name" :value="__('Skill name')" />
                <x-text-input id="skill_name" name="name" type="text" list="skill-options" class="mt-1 block w-full"
                    :value="old('name')" placeholder="e.g. Guitar, Python, Spanish" />
                <datalist id="skill-options">
                    @foreach (\App\Models\Skill::orderBy('name')->get() as $option)
                        <option value="{{ $option->name }}"></option>
                    @endforeach
                </datalist>
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="proficiency" :value="__('Level (if teaching)')" />
                <select id="proficiency" name="proficiency"
                    class="mt-1 block w-full rounded-md border-beige-300 shadow-sm focus:border-sage-500 focus:ring-sage-500">
                    <option value="">N/A</option>
                    <option value="beginner" @selected(old('proficiency') === 'beginner')>Beginner</option>
                    <option value="intermediate" @selected(old('proficiency') === 'intermediate')>Intermediate</option>
                    <option value="advanced" @selected(old('proficiency') === 'advanced')>Advanced</option>
                </select>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <div class="inline-flex rounded-md shadow-sm ring-1 ring-inset ring-beige-300">
                <label class="cursor-pointer">
                    <input type="radio" name="type" value="teach" class="peer sr-only" @checked(old('type', 'teach') === 'teach') />
                    <span class="inline-block rounded-l-md px-4 py-2 text-sm font-medium peer-checked:bg-sage-600 peer-checked:text-white">
                        I can teach this
                    </span>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="type" value="learn" class="peer sr-only" @checked(old('type') === 'learn') />
                    <span class="inline-block rounded-r-md px-4 py-2 text-sm font-medium peer-checked:bg-sage-600 peer-checked:text-white">
                        I want to learn this
                    </span>
                </label>
            </div>

            <x-primary-button>{{ __('Add skill') }}</x-primary-button>
        </div>
    </form>
</section>
