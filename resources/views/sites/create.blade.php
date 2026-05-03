@extends('layouts.app')

@section('title', 'Add Site - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>Add Site</h1>
                <p>Create a location where incidents, video sources, cameras, and evidence can be grouped.</p>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    <div class="card">
        <h2>Site Details</h2>

        <form method="POST" action="{{ route('sites.store') }}">
            @csrf

            @include('sites._form', ['site' => null, 'organizations' => $organizations])

            <button type="submit" class="btn">Create Site</button>
            <a href="{{ route('sites.index') }}" class="btn btn-secondary">Back to Sites</a>
        </form>
    </div>
@endsection
