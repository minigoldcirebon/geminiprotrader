<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Register</title>
    
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
                                    <span class="icon"><i class="mdi mdi-account-plus"></i></span>
                                    Create New Account
                                </p>
                            </header>
                            <div class="card-content">
                                <form method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <!-- Name -->
                                    <div class="field">
                                        <label class="label" for="name">{{ __('Name') }}</label>
                                        <div class="control has-icons-left">
                                            <input id="name" class="input @error('name') is-danger @enderror" 
                                                   type="text" name="name" value="{{ old('name') }}" 
                                                   required autofocus autocomplete="name" 
                                                   placeholder="Enter your full name">
                                            <span class="icon is-small is-left">
                                                <i class="mdi mdi-account"></i>
                                            </span>
                                        </div>
                                        @error('name')
                                            <p class="help is-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Email Address -->
                                    <div class="field">
                                        <label class="label" for="email">{{ __('Email') }}</label>
                                        <div class="control has-icons-left">
                                            <input id="email" class="input @error('email') is-danger @enderror" 
                                                   type="email" name="email" value="{{ old('email') }}" 
                                                   required autocomplete="username" 
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
                                                   required autocomplete="new-password"
                                                   placeholder="Enter your password">
                                            <span class="icon is-small is-left">
                                                <i class="mdi mdi-lock"></i>
                                            </span>
                                        </div>
                                        @error('password')
                                            <p class="help is-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="field">
                                        <label class="label" for="password_confirmation">{{ __('Confirm Password') }}</label>
                                        <div class="control has-icons-left">
                                            <input id="password_confirmation" class="input @error('password_confirmation') is-danger @enderror" 
                                                   type="password" name="password_confirmation" 
                                                   required autocomplete="new-password"
                                                   placeholder="Confirm your password">
                                            <span class="icon is-small is-left">
                                                <i class="mdi mdi-lock-check"></i>
                                            </span>
                                        </div>
                                        @error('password_confirmation')
                                            <p class="help is-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="field is-grouped is-grouped-right">
                                        <div class="control">
                                            <a class="button is-text" href="{{ route('login') }}">
                                                {{ __('Already registered?') }}
                                            </a>
                                        </div>
                                        <div class="control">
                                            <button type="submit" class="button is-primary">
                                                <span class="icon"><i class="mdi mdi-account-plus"></i></span>
                                                <span>{{ __('Register') }}</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                
                                <hr>
                                <div class="has-text-centered">
                                    <p>Already have an account? 
                                        <a href="{{ route('login') }}" class="has-text-primary">Login here</a>
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
