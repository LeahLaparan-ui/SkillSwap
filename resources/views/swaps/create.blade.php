<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-beige-800 leading-tight">
            {{ __('Propose a skill swap') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Receiver summary --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center gap-4">
                    <x-avatar :user="$receiver" size="lg" />
                    <div>
                        <p class="text-sm text-beige-500">You're proposing a swap with</p>
                        <a href="{{ route('students.show', $receiver) }}" class="text-lg font-bold text-beige-900 hover:text-sage-600">
                            {{ $receiver->name }}
                        </a>
                        @if ($receiver->university)
                            <p class="text-sm text-beige-500">{{ $receiver->university }}</p>
                        @endif
                    </div>
                </div>
            </div>

            @if ($myTeachSkills->isEmpty())
                <div class="rounded-lg bg-amber-50 p-4 text-sm text-amber-800 ring-1 ring-inset ring-amber-600/20">
                    You haven't listed any skills to teach yet.
                    <a href="{{ route('profile.edit') }}" class="font-semibold underline">Add a teaching skill</a>
                    before proposing a swap.
                </div>
            @elseif ($theirTeachSkills->isEmpty())
                <div class="rounded-lg bg-amber-50 p-4 text-sm text-amber-800 ring-1 ring-inset ring-amber-600/20">
                    {{ $receiver->name }} hasn't listed any teaching skills yet, so there's nothing to request.
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form method="POST" action="{{ route('swaps.store', $receiver) }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="offered_skill_id" :value="__('What will you teach?')" />
                            <select id="offered_skill_id" name="offered_skill_id" required
                                class="mt-1 block w-full rounded-md border-beige-300 shadow-sm focus:border-sage-500 focus:ring-sage-500">
                                <option value="">Select a skill you can teach</option>
                                @foreach ($myTeachSkills as $skill)
                                    <option value="{{ $skill->id }}" @selected(old('offered_skill_id') == $skill->id)>
                                        {{ $skill->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-beige-500">Choose one of the skills on your profile.</p>
                        </div>

                        <div>
                            <x-input-label for="requested_skill_id" :value="__('What do you want to learn?')" />
                            <select id="requested_skill_id" name="requested_skill_id" required
                                class="mt-1 block w-full rounded-md border-beige-300 shadow-sm focus:border-sage-500 focus:ring-sage-500">
                                <option value="">Select a skill {{ $receiver->name }} teaches</option>
                                @foreach ($theirTeachSkills as $skill)
                                    <option value="{{ $skill->id }}" @selected(old('requested_skill_id') == $skill->id)>
                                        {{ $skill->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="message" :value="__('Message (optional)')" />
                            <textarea id="message" name="message" rows="4"
                                class="mt-1 block w-full rounded-md border-beige-300 shadow-sm focus:border-sage-500 focus:ring-sage-500"
                                placeholder="Say hello and suggest when you'd like to start.">{{ old('message') }}</textarea>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit"
                                class="rounded-md bg-sage-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sage-500">
                                Send swap request
                            </button>
                            <a href="{{ route('students.show', $receiver) }}" class="text-sm font-medium text-beige-600 hover:text-beige-900">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
