<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Expert Trading Signals - Simplified') }}
        </h2>
    </x-slot>

    <style>
        .dark-bg { 
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); 
            min-height: 100vh;
        }
        .signal-card { 
            background: rgba(30, 41, 59, 0.95); 
            backdrop-filter: blur(10px); 
        }
    </style>

    <div class="dark-bg">
        <div class="page-container">
            <h1 class="page-title text-white text-center text-3xl font-bold mb-8">Expert Trading Signals</h1>
            
            @if($latestSignal)
            <div class="signal-card p-6 rounded-lg text-white mb-8">
                <h2 class="text-2xl font-bold mb-4">{{ $latestSignal->pair }}</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-gray-300">Signal Type</p>
                        <p class="font-semibold">{{ $latestSignal->signal_type }}</p>
                    </div>
                    <div>
                        <p class="text-gray-300">Confidence</p>
                        <p class="font-semibold">{{ $latestSignal->confidence_level }}%</p>
                    </div>
                    <div>
                        <p class="text-gray-300">Status</p>
                        <p class="font-semibold">{{ $latestSignal->status }}</p>
                    </div>
                    <div>
                        <p class="text-gray-300">Created</p>
                        <p class="font-semibold">{{ $latestSignal->created_at->format('M d, H:i') }}</p>
                    </div>
                </div>
                
                <!-- Placeholder for charts -->
                <div class="mt-6 p-4 bg-gray-700 rounded">
                    <p class="text-gray-300">Charts would be displayed here</p>
                    <p class="text-sm text-gray-400">Chart data points: {{ $latestSignal->chartDataPoints ? $latestSignal->chartDataPoints->count() : 0 }}</p>
                </div>
            </div>
            @endif

            @if($otherSignals && $otherSignals->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($otherSignals as $signal)
                <div class="signal-card p-6 rounded-lg text-white">
                    <h3 class="text-xl font-bold mb-4">{{ $signal->pair }}</h3>
                    <div class="space-y-2">
                        <p><span class="text-gray-300">Type:</span> {{ $signal->signal_type }}</p>
                        <p><span class="text-gray-300">Confidence:</span> {{ $signal->confidence_level }}%</p>
                        <p><span class="text-gray-300">Created:</span> {{ $signal->created_at->format('M d, H:i') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-6">
                {{ $otherSignals->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
