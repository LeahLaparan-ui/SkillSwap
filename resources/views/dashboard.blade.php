<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-beige-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Profile summary --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col gap-6 sm:flex-row sm:items-start">
                    <x-avatar :user="$user" size="xl" />

                    <div class="flex-1">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <h3 class="text-xl font-bold text-beige-900">{{ $user->name }}</h3>
                                @if ($user->university)
                                    <p class="text-sm text-beige-500">{{ $user->university }}</p>
                                @endif
                            </div>
                            <a href="{{ route('profile.edit') }}"
                               class="rounded-md bg-white px-3 py-1.5 text-sm font-semibold text-beige-700 ring-1 ring-inset ring-beige-300 hover:bg-beige-50">
                                Edit profile &amp; skills
                            </a>
                        </div>

                        @if ($user->bio)
                            <p class="mt-3 text-sm text-beige-600">{{ $user->bio }}</p>
                        @else
                            <p class="mt-3 text-sm italic text-beige-400">You haven't added a bio yet.</p>
                        @endif

                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <div>
                                <h4 class="text-xs font-semibold uppercase tracking-wide text-beige-500">Skills I teach</h4>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @forelse ($user->teachSkills as $skill)
                                        <x-skill-badge :skill="$skill" type="teach" />
                                    @empty
                                        <p class="text-sm text-beige-400">None yet.</p>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold uppercase tracking-wide text-beige-500">Skills I want to learn</h4>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @forelse ($user->learnSkills as $skill)
                                        <x-skill-badge :skill="$skill" type="learn" />
                                    @empty
                                        <p class="text-sm text-beige-400">None yet.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Swap activity --}}
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="border-b border-beige-100 px-6 py-4">
                        <h3 class="font-semibold text-beige-800">Incoming requests</h3>
                        <p class="text-sm text-beige-500">Students who want to swap with you.</p>
                    </div>
                    <div class="space-y-3 p-6">
                        @forelse ($incoming as $swapRequest)
                            @include('swaps.partials.request-card', ['swapRequest' => $swapRequest, 'direction' => 'incoming'])
                        @empty
                            <p class="text-sm text-beige-400">No pending incoming requests.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="border-b border-beige-100 px-6 py-4">
                        <h3 class="font-semibold text-beige-800">Outgoing requests</h3>
                        <p class="text-sm text-beige-500">Swaps you have proposed.</p>
                    </div>
                    <div class="space-y-3 p-6">
                        @forelse ($outgoing as $swapRequest)
                            @include('swaps.partials.request-card', ['swapRequest' => $swapRequest, 'direction' => 'outgoing'])
                        @empty
                            <p class="text-sm text-beige-400">You haven't sent any pending requests.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Suggested matches --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="border-b border-beige-100 px-6 py-4">
                    <h3 class="font-semibold text-beige-800">Suggested swap matches</h3>
                    <p class="text-sm text-beige-500">Students who teach what you want to learn and want to learn what you teach.</p>
                </div>
                <div class="p-6">
                    @if ($matches->isEmpty())
                        <p class="text-sm text-beige-400">
                            No mutual matches yet. Add more teaching and learning skills to your profile, then
                            <a href="{{ route('skills.index') }}" class="font-medium text-sage-600 hover:text-sage-500">browse students</a>.
                        </p>
                    @else
                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($matches as $match)
                                <div class="rounded-lg border border-beige-200 p-4">
                                    <div class="flex items-center gap-3">
                                        <x-avatar :user="$match" size="md" />
                                        <div class="min-w-0">
                                            <a href="{{ route('students.show', $match) }}" class="font-semibold text-beige-900 hover:text-sage-600">
                                                {{ $match->name }}
                                            </a>
                                            @if ($match->university)
                                                <p class="truncate text-xs text-beige-500">{{ $match->university }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-3 flex flex-wrap gap-1.5">
                                        @foreach ($match->teachSkills as $skill)
                                            <x-skill-badge :skill="$skill" type="teach" />
                                        @endforeach
                                    </div>
                                    <a href="{{ route('swaps.create', $match) }}"
                                       class="mt-4 block rounded-md bg-sage-600 px-3 py-2 text-center text-sm font-semibold text-white hover:bg-sage-500">
                                        Request swap
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
