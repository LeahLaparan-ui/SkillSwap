<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-beige-800 leading-tight">
            {{ __('Swap requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="border-b border-beige-100 px-6 py-4">
                    <h3 class="font-semibold text-beige-800">Incoming</h3>
                    <p class="text-sm text-beige-500">Requests other students have sent you.</p>
                </div>
                <div class="space-y-3 p-6">
                    @forelse ($incoming as $swapRequest)
                        @include('swaps.partials.request-card', ['swapRequest' => $swapRequest, 'direction' => 'incoming'])
                    @empty
                        <p class="text-sm text-beige-400">No incoming requests yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="border-b border-beige-100 px-6 py-4">
                    <h3 class="font-semibold text-beige-800">Outgoing</h3>
                    <p class="text-sm text-beige-500">Requests you have sent to other students.</p>
                </div>
                <div class="space-y-3 p-6">
                    @forelse ($outgoing as $swapRequest)
                        @include('swaps.partials.request-card', ['swapRequest' => $swapRequest, 'direction' => 'outgoing'])
                    @empty
                        <p class="text-sm text-beige-400">You haven't sent any requests yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
