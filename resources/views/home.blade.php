@extends('layouts.app')

@section('title', 'Home - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>FSV Incident</h1>
                <p>Welcome back. Continue to your incident dashboard.</p>

                <div style="margin-top: 18px;">
                    <a href="{{ route('incidents.index') }}" class="btn">Go to Incidents</a>
                </div>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>
@endsection
