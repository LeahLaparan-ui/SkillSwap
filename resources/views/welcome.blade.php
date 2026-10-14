<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'SwapSkills') }} — Trade skills with other students</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-beige-900">
        <div class="relative min-h-screen overflow-hidden bg-white">
            <div class="pointer-events-none absolute -top-40 -right-32 h-96 w-96 rounded-full bg-sage-100 blur-3xl"></div>
            <div class="pointer-events-none absolute top-40 -left-32 h-96 w-96 rounded-full bg-beige-200 blur-3xl"></div>

            <header class="relative z-10 mx-auto flex max-w-7xl items-center justify-between px-4 py-6 sm:px-6 lg:px-8">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="SwapSkills" class="h-9 w-9 rounded-lg object-cover" />
                    <span class="text-lg font-bold">SwapSkills</span>
                </div>

                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="rounded-md bg-sage-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sage-500">
                            Go to dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-beige-600 hover:text-beige-900">Log in</a>
                        <a href="{{ route('register') }}" class="rounded-md bg-sage-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sage-500">
                            Get started
                        </a>
                    @endauth
                </nav>
            </header>

            <main class="relative z-10 mx-auto max-w-7xl px-4 pb-24 sm:px-6 lg:px-8">
                <section class="mx-auto max-w-3xl pt-16 text-center">
                    <span class="inline-flex items-center rounded-full bg-sage-50 px-3 py-1 text-sm font-medium text-sage-700 ring-1 ring-inset ring-sage-600/20">
                        Learn together. Teach together.
                    </span>
                    <h1 class="mt-6 text-4xl font-bold tracking-tight text-beige-900 sm:text-5xl">
                        Swap skills with students who love what you want to learn
                    </h1>
                    <p class="mx-auto mt-6 max-w-2xl text-lg text-beige-600">
                        SwapSkills connects students who want to learn a skill with students who can teach it.
                        Offer what you know, get taught what you don't. No money — just a fair exchange.
                    </p>
                    <div class="mt-10 flex items-center justify-center gap-4">
                        <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="rounded-md bg-sage-600 px-6 py-3 text-base font-semibold text-white shadow-sm hover:bg-sage-500">
                            Find a swap partner
                        </a>
                        <a href="{{ route('login') }}" class="rounded-md bg-white px-6 py-3 text-base font-semibold text-beige-700 ring-1 ring-inset ring-beige-300 hover:bg-beige-50">
                            Log in
                        </a>
                    </div>
                </section>

                <section class="mt-24 grid gap-8 sm:grid-cols-3">
                    <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-beige-100">
                        <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-sage-600 text-white">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="mt-5 text-lg font-semibold">Build your profile</h3>
                        <p class="mt-2 text-sm text-beige-600">List the skills you can teach and the skills you want to pick up.</p>
                    </div>
                    <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-beige-100">
                        <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-sage-600 text-white">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z" />
                            </svg>
                        </div>
                        <h3 class="mt-5 text-lg font-semibold">Find a match</h3>
                        <p class="mt-2 text-sm text-beige-600">Search by skill and discover students who teach exactly what you want to learn.</p>
                    </div>
                    <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-beige-100">
                        <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-rose-500 text-white">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                            </svg>
                        </div>
                        <h3 class="mt-5 text-lg font-semibold">Swap and grow</h3>
                        <p class="mt-2 text-sm text-beige-600">Send a swap request, accept the ones you like, and start learning together.</p>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
