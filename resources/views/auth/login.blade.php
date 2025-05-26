<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Login</title>
    
    <!-- Admin One CSS -->
    <link rel="stylesheet" href="{{ asset('admin-one/css/main.css') }}">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/6.5.95/css/materialdesignicons.min.css">
</head>
<body>
    <div id="app">
        <section class="section main-section">
            <div class="container">
                <div class="columns is-centered">
                    <div class="column is-half">
                        <div class="card">
                            <header class="card-header">
                                <p class="card-header-title">
                                    <span class="icon"><i class="mdi mdi-account-circle"></i></span>
                                    Login to Your Account
                                </p>
                            </header>
                            <div class="card-content">
                                <!-- Session Status -->
                                @if (session('status'))
                                    <div class="notification is-success">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <!-- Email Address -->
                                    <div class="field">
                                        <label class="label" for="email">{{ __('Email') }}</label>
                                        <div class="control has-icons-left">
                                            <input id="email" class="input @error('email') is-danger @enderror" 
                                                   type="email" name="email" value="{{ old('email') }}" 
                                                   required autofocus autocomplete="username" 
                                                   placeholder="Enter your email">
                                            <span class="icon is-small is-left">
                                                <i class="mdi mdi-email"></i>
                                            </span>
                                        </div>
                                        @error('email')
                                            <p class="help is-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="field">
                                        <label class="label" for="password">{{ __('Password') }}</label>
                                        <div class="control has-icons-left">
                                            <input id="password" class="input @error('password') is-danger @enderror" 
                                                   type="password" name="password" 
                                                   required autocomplete="current-password"
                                                   placeholder="Enter your password">
                                            <span class="icon is-small is-left">
                                                <i class="mdi mdi-lock"></i>
                                            </span>
                                        </div>
                                        @error('password')
                                            <p class="help is-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Remember Me -->
                                    <div class="field">
                                        <div class="control">
                                            <label class="checkbox">
                                                <input type="checkbox" name="remember" id="remember_me">
                                                {{ __('Remember me') }}
                                            </label>
                                        </div>
                                    </div>

                                    <div class="field is-grouped is-grouped-right">
                                        @if (Route::has('password.request'))
                                            <div class="control">
                                                <a class="button is-text" href="{{ route('password.request') }}">
                                                    {{ __('Forgot your password?') }}
                                                </a>
                                            </div>
                                        @endif
                                        <div class="control">
                                            <button type="submit" class="button is-primary">
                                                <span class="icon"><i class="mdi mdi-login"></i></span>
                                                <span>{{ __('Log in') }}</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                
                                <hr>
                                <div class="has-text-centered">
                                    <p>Don't have an account? 
                                        <a href="{{ route('register') }}" class="has-text-primary">Register here</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    <!-- Admin One JS -->
    <script src="{{ asset('admin-one/js/main.js') }}"></script>
</body>
</html>
