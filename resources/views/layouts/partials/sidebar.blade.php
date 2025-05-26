<div class="flex h-full flex-col bg-gray-800 text-gray-300 w-64 fixed inset-y-0 left-0 z-30">
    <div class="p-4 mb-4 border-b border-gray-700 flex items-center justify-center">
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <x-application-logo class="block h-9 w-auto fill-current text-white" />
            <span class="ml-3 text-lg font-semibold text-white">{{ config('app.name', 'Laravel') }}</span>
        </a>
    </div>

    <nav class="flex-1 px-2 space-y-1 overflow-y-auto">
        {{-- Dashboard Pengguna --}}
        <x-nav-link-sidebar :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <svg class="mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
            {{ __('Dashboard') }}
        </x-nav-link-sidebar>

        {{-- Gemini RealTime Signal --}}
        <x-nav-link-sidebar :href="route('signals.realtime')" :active="request()->routeIs('signals.realtime') || request()->routeIs('signals.history')">
            <svg class="mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 20l4-4m0 0l4-4m-4 4v5m-4-2h8" /></svg>
            {{ __('Gemini RealTime Signal') }}
        </x-nav-link-sidebar>

        {{-- Gemini Expert Signal --}}
        <x-nav-link-sidebar :href="route('expert-signals.public')" :active="request()->routeIs('expert-signals.public') || request()->routeIs('expert-signals.show')">
            <svg class="mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            {{ __('Gemini Expert Signal') }}
        </x-nav-link-sidebar>

        {{-- BOT Trading Hub --}}
        <x-nav-link-sidebar :href="route('bots.index')" :active="request()->routeIs('bots.index') || request()->routeIs('bots.show')">
            <svg class="mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h10l-1-1-0.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            {{ __('BOT Trading Hub') }}
        </x-nav-link-sidebar>

        {{-- Penagihan & Langganan (Billing & Subscriptions) dengan dropdown --}}
        <div x-data="{ open: {{ request()->routeIs('billing.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="flex items-center justify-between w-full px-2 py-2 text-sm font-medium text-left rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" :class="{'bg-gray-700 text-white': open, 'text-gray-300 hover:bg-gray-700 hover:text-white': !open}">
                <span class="flex items-center">
                    <svg class="mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    {{ __('Penagihan & Langganan') }}
                </span>
                <svg :class="{'rotate-90': open, 'rotate-0': !open}" class="ml-auto h-5 w-5 transform transition-transform duration-150 ease-in-out" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
            </button>
            <div x-show="open" class="mt-1 space-y-1 pl-7" x-cloak>
                <x-nav-link-sidebar :href="route('billing.index')" :active="request()->routeIs('billing.index')">Langganan Saya</x-nav-link-sidebar>
                <x-nav-link-sidebar :href="route('billing.history')" :active="request()->routeIs('billing.history')">Riwayat Pembayaran</x-nav-link-sidebar>
                {{-- Jika ada route untuk 'Auto Recharge', tambahkan di sini --}}
                {{-- <x-nav-link-sidebar href="#">Auto Recharge</x-nav-link-sidebar> --}}
            </div>
        </div>

        {{-- Dukungan & Sumber Daya --}}
        {{-- (Asumsikan belum ada route, jadi href="#" untuk sementara) --}}
        <div x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center justify-between w-full px-2 py-2 text-sm font-medium text-left rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" :class="{'bg-gray-700 text-white': open, 'text-gray-300 hover:bg-gray-700 hover:text-white': !open}">
                <span class="flex items-center">
                     <svg class="mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" /></svg>
                    {{ __('Dukungan & Sumber Daya') }}
                </span>
                <svg :class="{'rotate-90': open, 'rotate-0': !open}" class="ml-auto h-5 w-5 transform transition-transform duration-150 ease-in-out" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
            </button>
            <div x-show="open" class="mt-1 space-y-1 pl-7" x-cloak>
                <x-nav-link-sidebar href="#">Pusat Bantuan/FAQ</x-nav-link-sidebar>
                <x-nav-link-sidebar href="#">Hubungi Dukungan</x-nav-link-sidebar>
                {{-- <x-nav-link-sidebar href="#">Tutorial/Panduan</x-nav-link-sidebar> --}}
            </div>
        </div>

        {{-- Jika pengguna adalah admin, tampilkan link ke Panel Admin --}}
        @if(Auth::user()->is_admin) {{-- Asumsi ada method is_admin() di model User --}}
            <div class="pt-4 pb-2 px-2 mt-4 border-t border-gray-700">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Admin</span>
            </div>
            <x-nav-link-sidebar :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                 <svg class="mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                {{ __('Admin Panel') }}
            </x-nav-link-sidebar>
        @endif

    </nav>

    <div class="mt-auto p-2 border-t border-gray-700">
        <div x-data="{ open: false }" class="relative">
            <div>
                <button @click="open = !open" type="button" class="group w-full bg-gray-800 rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white flex items-center justify-between" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                    <span class="flex min-w-0 items-center justify-between">
                         <svg class="mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span class="truncate text-sm font-medium text-gray-300 group-hover:text-white">{{ Auth::user()->name }}</span>
                    </span>
                    <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="ml-2 h-5 w-5 text-gray-400 group-hover:text-gray-300 transform transition-transform duration-150 ease-in-out" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div x-show="open"
                 @click.outside="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute bottom-full left-0 z-40 mb-1 w-full origin-bottom-left rounded-md bg-white py-1.5 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                 role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                 style="display: none;" x-cloak>
                <a href="{{ route('profile.edit') }}" class="block px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-50" role="menuitem" tabindex="-1">
                    {{ __('Profile') }}
                </a>
                <form method="POST" action="{{ route('logout') }}" role="none">
                    @csrf
                    <button type="submit" class="block w-full px-3 py-1 text-left text-sm leading-6 text-gray-900 hover:bg-gray-50" role="menuitem" tabindex="-1">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>