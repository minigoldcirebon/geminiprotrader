<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Expert Trading Signals - Debug') }}
        </h2>
    </x-slot>

    <style>
        .dark-bg { 
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); 
            min-height: 100vh;
        }
        .debug-info {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            color: #333;
        }
    </style>

    <div class="dark-bg">
        <div class="debug-info">
            <h1>Expert Signals Debug Information</h1>
            
            <h3>Latest Signal:</h3>
            @if($latestSignal)
                <p><strong>✅ Latest Signal Found:</strong> {{ $latestSignal->pair }} ({{ $latestSignal->signal_type }})</p>
                <p><strong>Status:</strong> {{ $latestSignal->status }}</p>
                <p><strong>Created:</strong> {{ $latestSignal->created_at }}</p>
            @else
                <p><strong>❌ No Latest Signal Found</strong></p>
            @endif

            <h3>Other Signals:</h3>
            <p><strong>Count:</strong> {{ $otherSignals->count() }}</p>
            <p><strong>Total:</strong> {{ $otherSignals->total() }}</p>

            <h3>All Signals List:</h3>
            @foreach($otherSignals as $signal)
                <div style="border: 1px solid #ccc; padding: 10px; margin: 5px 0;">
                    <strong>{{ $signal->pair }}</strong> - {{ $signal->signal_type }} 
                    ({{ $signal->status }}) - {{ $signal->created_at }}
                </div>
            @endforeach

            <h3>Statistics:</h3>
            <pre>{{ json_encode($stats, JSON_PRETTY_PRINT) }}</pre>

            <h3>Filter Options:</h3>
            <p><strong>Pairs:</strong> {{ implode(', ', $pairs->toArray()) }}</p>
            <p><strong>Timeframes:</strong> {{ implode(', ', $timeframes->toArray()) }}</p>
        </div>
    </div>
</x-app-layout>
