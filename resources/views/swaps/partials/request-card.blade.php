@props(['swapRequest', 'direction'])

@php
    $other = $direction === 'incoming' ? $swapRequest->sender : $swapRequest->receiver;
@endphp

<div class="rounded-lg border border-beige-200 p-4">
    <div class="flex items-start gap-4">
        <x-avatar :user="$other" size="md" />

        <div class="min-w-0 flex-1">
            <div class="flex items-center justify-between gap-2">
                <a href="{{ route('students.show', $other) }}" class="font-semibold text-beige-900 hover:text-sage-600">
                    {{ $other->name }}
                </a>
                <x-status-badge :status="$swapRequest->status" />
            </div>

            <p class="mt-1 text-sm text-beige-600">
                @if ($direction === 'incoming')
                    Wants to learn
                    <span class="font-medium text-beige-800">{{ $swapRequest->requestedSkill->name }}</span>
                    and will teach you
                    <span class="font-medium text-beige-800">{{ $swapRequest->offeredSkill->name }}</span>.
                @else
                    You offered to teach
                    <span class="font-medium text-beige-800">{{ $swapRequest->offeredSkill->name }}</span>
                    in exchange for
                    <span class="font-medium text-beige-800">{{ $swapRequest->requestedSkill->name }}</span>.
                @endif
            </p>

            @if ($swapRequest->message)
                <p class="mt-2 rounded-md bg-beige-50 p-3 text-sm italic text-beige-700">"{{ $swapRequest->message }}"</p>
            @endif

            <div class="mt-3 flex flex-wrap gap-2">
                @if ($direction === 'incoming' && $swapRequest->isPending())
                    <form method="POST" action="{{ route('swaps.update', $swapRequest) }}">
                        @csrf
                        @method('patch')
                        <input type="hidden" name="action" value="accept" />
                        <button class="rounded-md bg-sage-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-sage-500">
                            Accept
                        </button>
                    </form>
                    <form method="POST" action="{{ route('swaps.update', $swapRequest) }}">
                        @csrf
                        @method('patch')
                        <input type="hidden" name="action" value="decline" />
                        <button class="rounded-md bg-white px-3 py-1.5 text-xs font-semibold text-beige-700 ring-1 ring-inset ring-beige-300 hover:bg-beige-50">
                            Decline
                        </button>
                    </form>
                @elseif ($direction === 'outgoing' && $swapRequest->isPending())
                    <form method="POST" action="{{ route('swaps.update', $swapRequest) }}">
                        @csrf
                        @method('patch')
                        <input type="hidden" name="action" value="cancel" />
                        <button class="rounded-md bg-white px-3 py-1.5 text-xs font-semibold text-beige-700 ring-1 ring-inset ring-beige-300 hover:bg-beige-50">
                            Cancel request
                        </button>
                    </form>
                @elseif ($swapRequest->status === \App\Models\SwapRequest::STATUS_ACCEPTED)
                    <form method="POST" action="{{ route('swaps.update', $swapRequest) }}">
                        @csrf
                        @method('patch')
                        <input type="hidden" name="action" value="complete" />
                        <button class="rounded-md bg-sage-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-sage-500">
                            Mark as complete
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
