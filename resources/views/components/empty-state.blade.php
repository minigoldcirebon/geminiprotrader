@props([
    'title' => 'No data found',
    'description' => 'Get started by creating a new item.',
    'icon' => 'document',
    'actionText' => null,
    'actionUrl' => null
])

<div class="text-center py-12">
    <div class="mx-auto h-12 w-12 text-gray-400">
        @if($icon === 'document')
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-12 w-12">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        @elseif($icon === 'users')
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-12 w-12">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
        @elseif($icon === 'chart')
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-12 w-12">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        @elseif($icon === 'inbox')
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-12 w-12">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
        @else
            {!! $icon !!}
        @endif
    </div>
    
    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ $title }}</h3>
    <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
    
    @if($actionText && $actionUrl)
        <div class="mt-6">
            <x-button variant="primary" href="{{ $actionUrl }}">
                {{ $actionText }}
            </x-button>
        </div>
    @endif
    
    {{ $slot }}
</div>
