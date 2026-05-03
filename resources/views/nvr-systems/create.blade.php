@extends('layouts.app')

@section('title', 'Add NVR/VMS - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>Add NVR/VMS</h1>
                <p>Create a video system profile for a customer site.</p>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    <div class="card">
        <h2>System Details</h2>

        <form method="POST" action="{{ route('nvr-systems.store') }}">
            @csrf

            @include('nvr-systems._form', ['nvrSystem' => null, 'sites' => $sites])

            <button type="submit" class="btn">Create NVR/VMS</button>
            <a href="{{ route('nvr-systems.index') }}" class="btn btn-secondary">Back to NVR/VMS</a>
        </form>
    </div>
@endsection
