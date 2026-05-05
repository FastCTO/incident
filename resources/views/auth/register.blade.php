@extends('layouts.app')

@section('title', 'Register - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>Register</h1>
                <p>Create an FSV Incident account.</p>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    <div class="card">
        <h2>Create Account</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="field">
                <label for="name">Name *</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label for="organization_name">Business / Organization Name</label>
                <input id="organization_name" type="text" name="organization_name" value="{{ old('organization_name') }}">
                @error('organization_name') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="grid">
                <div class="field">
                    <label for="email">Email Address *</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="cell_phone">Cell Phone</label>
                    <input id="cell_phone" type="text" name="cell_phone" value="{{ old('cell_phone') }}">
                    @error('cell_phone') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid">
                <div class="field">
                    <label for="password">Password *</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password">
                    @error('password') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="password-confirm">Confirm Password *</label>
                    <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
                </div>
            </div>

            <button type="submit" class="btn">Register</button>
            <a href="{{ route('free-checkup.create') }}" class="btn btn-secondary">Start Free Checkup</a>
            <a href="{{ route('login') }}" class="btn btn-secondary">Already Have an Account?</a>
        </form>
    </div>
@endsection
