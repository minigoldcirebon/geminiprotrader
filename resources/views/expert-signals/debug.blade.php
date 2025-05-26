<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expert Signals Debug</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #1a1a2e; 
            color: white; 
            padding: 20px; 
        }
        .debug-info { 
            background: #2a2a4e; 
            padding: 20px; 
            border-radius: 8px; 
            margin-bottom: 20px; 
        }
    </style>
</head>
<body>
    <div class="debug-info">
        <h1>Expert Signals Debug Page</h1>
        <p><strong>Page loaded successfully!</strong></p>
        <p>Signals count: {{ count($signals) }}</p>
        <p>Time: {{ now() }}</p>
    </div>

    @if(count($signals) > 0)
        <div class="debug-info">
            <h2>First Signal Data:</h2>
            <pre>{{ json_encode($signals[0], JSON_PRETTY_PRINT) }}</pre>
        </div>
    @endif

    <div class="debug-info">
        <h2>All Signals:</h2>
        @foreach($signals as $signal)
            <div style="border: 1px solid #444; padding: 10px; margin: 10px 0; border-radius: 5px;">
                <strong>{{ $signal->pair }}</strong> - {{ $signal->signal_type }} 
                ({{ $signal->status }}) - Created: {{ $signal->created_at }}
            </div>
        @endforeach
    </div>

    <script>
        console.log('Debug page loaded successfully');
        console.log('Signals data:', @json($signals));
    </script>
</body>
</html>
