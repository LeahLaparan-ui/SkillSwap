<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-beige-800 leading-tight">
            {{ __('Browse students') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filters --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="GET" action="{{ route('skills.index') }}" class="grid gap-4 p-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="sm:col-span-2 lg:col-span-1">
                        <x-input-label for="q" :value="__('Search')" />
                        <x-text-input id="q" name="q" type="text" class="mt-1 block w-full"
                            :value="$search" placeholder="Name or university" />
                    </div>

                    <div>
                        <x-input-label for="skill" :value="__('Skill')" />
                        <select id="skill" name="skill"
                            class="mt-1 block w-full rounded-md border-beige-300 shadow-sm focus:border-sage-500 focus:ring-sage-500">
                            <option value="">Any skill</option>
                            @foreach ($skills as $skill)
                                <option value="{{ $skill->slug }}" @selected($skillSlug === $skill->slug)>
                                    {{ $skill->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="type" :value="__('Looking for')" />
                        <select id="type" name="type"
                            class="mt-1 block w-full rounded-md border-beige-300 shadow-sm focus:border-sage-500 focus:ring-sage-500">
                            <option value="teach" @selected($type === 'teach')>Students who teach this</option>
                            <option value="learn" @selected($type === 'learn')>Students who want to learn this</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full rounded-md bg-sage-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sage-500">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            {{-- Results --}}
            @if ($students->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-sm text-beige-500">
                        No students matched your search. Try a different skill or clear the filters.
                    </div>
                </div>
            @else
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($students as $student)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                            <div class="flex items-center gap-3">
                                <x-avatar :user="$student" size="md" />
                                <div class="min-w-0">
                                    <a href="{{ route('students.show', $student) }}" class="font-semibold text-beige-900 hover:text-sage-600">
                                        {{ $student->name }}
                                    </a>
                                    @if ($student->university)
                                        <p class="truncate text-xs text-beige-500">{{ $student->university }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4">
                                <h4 class="text-xs font-semibold uppercase tracking-wide text-beige-500">Teaches</h4>
                                <div class="mt-2 flex flex-wrap gap-1.5">
                                    @forelse ($student->teachSkills as $skill)
                                        <x-skill-badge :skill="$skill" type="teach" />
                                    @empty
                                        <span class="text-xs text-beige-400">Nothing listed</span>
                                    @endforelse
                                </div>
                            </div>

                            <div class="mt-3">
                                <h4 class="text-xs font-semibold uppercase tracking-wide text-beige-500">Wants to learn</h4>
                                <div class="mt-2 flex flex-wrap gap-1.5">
                                    @forelse ($student->learnSkills as $skill)
                                        <x-skill-badge :skill="$skill" type="learn" />
                                    @empty
                                        <span class="text-xs text-beige-400">Nothing listed</span>
                                    @endforelse
                                </div>
                            </div>

                            <div class="mt-4 flex gap-2">
                                <a href="{{ route('students.show', $student) }}"
                                   class="flex-1 rounded-md bg-white px-3 py-2 text-center text-sm font-semibold text-beige-700 ring-1 ring-inset ring-beige-300 hover:bg-beige-50">
                                    View profile
                                </a>
                                <a href="{{ route('swaps.create', $student) }}"
                                   class="flex-1 rounded-md bg-sage-600 px-3 py-2 text-center text-sm font-semibold text-white hover:bg-sage-500">
                                    Request swap
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-2">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
