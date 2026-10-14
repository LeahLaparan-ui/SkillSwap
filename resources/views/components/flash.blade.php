@php
    $messages = [
        'profile-updated' => 'Profile updated.',
        'skill-added' => 'Skill added.',
        'skill-removed' => 'Skill removed.',
        'swap-requested' => 'Swap request sent.',
        'swap-updated' => 'Swap request updated.',
    ];
    $statusMessage = session('status') ? ($messages[session('status')] ?? session('status')) : null;
@endphp

@if ($statusMessage)
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 4000)"
        x-transition.opacity
        class="fixed top-4 right-4 z-50 max-w-sm rounded-lg bg-sage-600 px-4 py-3 text-sm font-medium text-white shadow-lg"
    >
        {{ $statusMessage }}
    </div>
@endif

@if ($errors->any())
    <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-lg bg-rose-50 p-4 ring-1 ring-inset ring-rose-600/20">
            <ul class="list-inside list-disc space-y-1 text-sm text-rose-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
