Route::get('/', function () {
    return view('welcome');
});

// Test route with simulated auth (temporary)
Route::get('/test-auth-signals', function () {
    // Simulate authentication
    $user = \App\Models\User::where('email', 'test@example.com')->first();
    if ($user) {
        auth()->login($user);
        return redirect('/expert-signals');
    }
    return "Test user not found";
});

// Dashboard
