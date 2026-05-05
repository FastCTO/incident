@extends('layouts.app')

@section('title', 'Login - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>FSV Incident</h1>
                <p>Log in to manage incidents, evidence, sites, video sources, and checkups.</p>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    @if(session('status'))
        <div class="success">{{ session('status') }}</div>
    @endif

    <div class="card">
        <h2>Login</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="field">
                <label for="email">Email Address</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    autofocus
                >
                @error('email') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                >
                @error('password') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label style="display: flex; gap: 8px; align-items: center; font-weight: 400;">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>
            </div>

            <button type="submit" class="btn">Login</button>

            <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>

            @if(\Illuminate\Support\Facades\Route::has('free-checkup.create'))
                <a href="{{ route('free-checkup.create') }}" class="btn btn-secondary">Start Free Checkup</a>
            @endif

            @if(Route::has('password.request'))
                <div style="margin-top: 14px;">
                    <a href="{{ route('password.request') }}">Forgot your password?</a>
                </div>
            @endif
        </form>
    </div>
@endsection
