<!DOCTYPE html>
<html>
<head>
    <title>Simple Expert Signals Test</title>
</head>
<body>
    <h1>Expert Signals Test</h1>
    <p>Found {{ count($signals) }} signals</p>
    
    @foreach($signals as $signal)
        <div style="border: 1px solid #ccc; margin: 10px; padding: 10px;">
            <h3>{{ $signal->pair }}</h3>
            <p>Signal Type: {{ $signal->signal_type }}</p>
            <p>Status: {{ $signal->status }}</p>
            <p>Created: {{ $signal->created_at }}</p>
        </div>
    @endforeach
</body>
</html>
