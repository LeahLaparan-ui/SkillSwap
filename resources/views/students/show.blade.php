<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-beige-800 leading-tight">
            {{ __('Student profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col gap-6 sm:flex-row sm:items-start">
                    <x-avatar :user="$student" size="xl" />

                    <div class="flex-1">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <h3 class="text-2xl font-bold text-beige-900">{{ $student->name }}</h3>
                                @if ($student->university)
                                    <p class="text-sm text-beige-500">{{ $student->university }}</p>
                                @endif
                            </div>

                            @if (auth()->id() === $student->id)
                                <a href="{{ route('profile.edit') }}"
                                   class="rounded-md bg-white px-3 py-1.5 text-sm font-semibold text-beige-700 ring-1 ring-inset ring-beige-300 hover:bg-beige-50">
                                    Edit profile
                                </a>
                            @else
                                <a href="{{ route('swaps.create', $student) }}"
                                   class="rounded-md bg-sage-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sage-500">
                                    Request swap
                                </a>
                            @endif
                        </div>

                        @if ($student->bio)
                            <p class="mt-4 text-sm text-beige-600">{{ $student->bio }}</p>
                        @else
                            <p class="mt-4 text-sm italic text-beige-400">This student hasn't written a bio yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-beige-800">Can teach</h3>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @forelse ($student->teachSkills as $skill)
                            <x-skill-badge :skill="$skill" type="teach" />
                        @empty
                            <p class="text-sm text-beige-400">No teaching skills listed.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-beige-800">Wants to learn</h3>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @forelse ($student->learnSkills as $skill)
                            <x-skill-badge :skill="$skill" type="learn" />
                        @empty
                            <p class="text-sm text-beige-400">No learning goals listed.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            @if (auth()->id() !== $student->id)
                <div class="rounded-lg bg-sage-50 p-4 text-sm text-sage-700 ring-1 ring-inset ring-sage-600/20">
                    Want to trade skills with {{ $student->name }}?
                    <a href="{{ route('swaps.create', $student) }}" class="font-semibold underline">Send a swap request</a>.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
