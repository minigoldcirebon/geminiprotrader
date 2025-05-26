<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Layout Test - Expert Signals') }}
        </h2>
    </x-slot>

    <style>
        .test-bg { 
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); 
            min-height: 100vh;
            padding: 2rem;
        }
        .test-card {
            background: rgba(30, 41, 59, 0.95);
            border-radius: 1rem;
            padding: 2rem;
            margin: 1rem 0;
            color: white;
        }
    </style>

    <div class="test-bg">
        <div style="max-width: 1200px; margin: 0 auto;">
            <h1 style="color: white; text-align: center; font-size: 2rem; margin-bottom: 2rem;">Expert Signals Layout Test</h1>
            
            <div class="test-card">
                <h3>✅ Layout Component Working</h3>
                <p>Jika Anda melihat ini dengan styling yang benar, berarti x-app-layout berfungsi.</p>
            </div>

            @php
                $controller = new \App\Http\Controllers\ExpertSignalController();
                $result = $controller->index(request());
                $data = $result->getData();
            @endphp

            <div class="test-card">
                <h3>📊 Data Status:</h3>
                <ul>
                    <li><strong>Latest Signal:</strong> 
                        @if($data['latestSignal'])
                            ✅ {{ $data['latestSignal']->pair }} ({{ $data['latestSignal']->signal_type }})
                        @else
                            ❌ No latest signal
                        @endif
                    </li>
                    <li><strong>Other Signals:</strong> {{ $data['otherSignals']->count() }}</li>
                    <li><strong>Total Published:</strong> {{ $data['stats']['total_signals'] ?? 0 }}</li>
                </ul>
            </div>

            @if($data['latestSignal'])
            <div class="test-card">
                <h3>🎯 Latest Signal Details:</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <div style="background: rgba(59, 130, 246, 0.2); padding: 1rem; border-radius: 0.5rem;">
                        <div style="font-size: 1.5rem; font-weight: bold;">{{ $data['latestSignal']->pair }}</div>
                        <div style="color: #94a3b8;">Currency Pair</div>
                    </div>
                    <div style="background: rgba(16, 185, 129, 0.2); padding: 1rem; border-radius: 0.5rem;">
                        <div style="font-size: 1.5rem; font-weight: bold;">{{ $data['latestSignal']->signal_type }}</div>
                        <div style="color: #94a3b8;">Signal Type</div>
                    </div>
                    <div style="background: rgba(245, 158, 11, 0.2); padding: 1rem; border-radius: 0.5rem;">
                        <div style="font-size: 1.5rem; font-weight: bold;">${{ number_format($data['latestSignal']->entry_price, 5) }}</div>
                        <div style="color: #94a3b8;">Entry Price</div>
                    </div>
                    <div style="background: rgba(239, 68, 68, 0.2); padding: 1rem; border-radius: 0.5rem;">
                        <div style="font-size: 1.5rem; font-weight: bold;">{{ $data['latestSignal']->status }}</div>
                        <div style="color: #94a3b8;">Status</div>
                    </div>
                </div>
            </div>
            @endif

            <div class="test-card">
                <h3>🔗 Action Links:</h3>
                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <a href="/expert-signals" style="background: #3b82f6; color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; text-decoration: none;">
                        View Original Page
                    </a>
                    <a href="/expert-signals-no-auth" style="background: #10b981; color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; text-decoration: none;">
                        View No Auth
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
