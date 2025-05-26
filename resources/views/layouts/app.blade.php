<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @if(app()->environment('testing') || !file_exists(public_path('build/manifest.json')))
            <script src="https://cdn.tailwindcss.com"></script>
        @else
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        {{-- Pastikan Alpine.js di-load, biasanya sudah ada di app.js Breeze --}}
        {{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

    </head>
    <body class="font-sans antialiased h-full">
        <div class="flex h-full">
            @include('layouts.partials.sidebar')

            <div class="flex flex-col w-0 flex-1 overflow-hidden">
                {{-- Anda bisa menambahkan header navigasi atas di sini jika mau, atau menghapusnya --}}
                {{-- Contoh header atas sederhana jika diperlukan --}}
                <div class="relative z-10 flex h-16 flex-shrink-0 border-b border-gray-200 bg-white shadow-sm lg:hidden"> {{-- Header ini hanya untuk mobile jika sidebar utama fixed --}}
                    <button @click="open = true" type="button" class="border-r border-gray-200 px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 lg:hidden">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <div class="flex flex-1 justify-between px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-1">
                            {{-- Bisa isi search atau breadcrumb --}}
                        </div>
                        <div class="ml-4 flex items-center md:ml-6">
                            {{-- Tombol User & Logout untuk Mobile jika header atas ada --}}
                        </div>
                    </div>
                </div>

                <main class="flex-1 relative overflow-y-auto focus:outline-none">
                    @isset($header)
                        <header class="bg-white shadow-sm sticky top-0 z-20"> {{-- Dibuat sticky --}}
                            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <div class="py-6">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                            {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>