<!DOCTYPE html>
<html>
<head>
    <title>Simple Test</title>
    <style>
        body { background: #1a1a2e; color: white; padding: 20px; font-family: Arial; }
        .test-box { background: rgba(255,255,255,0.1); padding: 15px; margin: 10px 0; border-radius: 8px; }
    </style>
</head>
<body>
    <h1>Expert Signals Test</h1>
    
    <div class="test-box">
        <h3>Data Check:</h3>
        @php
            $controller = new \App\Http\Controllers\ExpertSignalController();
            $result = $controller->index(request());
            $data = $result->getData();
        @endphp
        
        <p><strong>Latest Signal:</strong> 
            @if($data['latestSignal'])
                ✅ Found: {{ $data['latestSignal']->pair }} ({{ $data['latestSignal']->signal_type }})
            @else
                ❌ Not Found
            @endif
        </p>
        
        <p><strong>Other Signals:</strong> {{ $data['otherSignals']->count() }}</p>
        <p><strong>Total Published:</strong> {{ $data['stats']['total_signals'] ?? 0 }}</p>
    </div>

    <div class="test-box">
        <h3>Sample Signal Card:</h3>
        @if($data['latestSignal'])
            <div style="background: rgba(30, 41, 59, 0.95); padding: 15px; border-radius: 8px;">
                <h4>{{ $data['latestSignal']->pair }}</h4>
                <p>Type: {{ $data['latestSignal']->signal_type }}</p>
                <p>Status: {{ $data['latestSignal']->status }}</p>
                <p>Entry: ${{ number_format($data['latestSignal']->entry_price, 5) }}</p>
            </div>
        @endif
    </div>

    <div class="test-box">
        <h3>Statistics:</h3>
        <ul>
            <li>Total: {{ $data['stats']['total_signals'] ?? 0 }}</li>
            <li>Profitable: {{ $data['stats']['profitable_signals'] ?? 0 }}</li>
            <li>Loss: {{ $data['stats']['loss_signals'] ?? 0 }}</li>
            <li>Pending: {{ $data['stats']['pending_signals'] ?? 0 }}</li>
        </ul>
    </div>
</body>
</html>
