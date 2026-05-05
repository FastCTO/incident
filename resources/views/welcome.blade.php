@extends('layouts.app')

@section('title', 'FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>FSV Incident</h1>
                <p>Evidence integrity workflows for surveillance video, incident response, and system checkups.</p>

                <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 18px;">
                    <a href="{{ route('login') }}" class="btn">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>

                    @if(\Illuminate\Support\Facades\Route::has('free-checkup.create'))
                        <a href="{{ route('free-checkup.create') }}" class="btn btn-secondary">Start Free Checkup</a>
                    @endif
                </div>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>
@endsection
