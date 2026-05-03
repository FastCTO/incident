@extends('layouts.app')

@section('title', 'Edit Site - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>Edit Site</h1>
                <p>{{ $site->name }}</p>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <h2>Site Details</h2>

        <form method="POST" action="{{ route('sites.update', $site) }}">
            @csrf
            @method('PUT')

            @include('sites._form', ['site' => $site, 'organizations' => $organizations])

            <button type="submit" class="btn">Update Site</button>
            <a href="{{ route('sites.index') }}" class="btn btn-secondary">Back to Sites</a>

            @if(\Illuminate\Support\Facades\Route::has('nvr-systems.create'))
                <a href="{{ route('nvr-systems.create') }}" class="btn btn-secondary">Add Video Source</a>
            @endif
        </form>
    </div>

    <div class="card">
        <h2>Site Summary</h2>

        <div class="details">
            <div>
                <div class="label">Site ID</div>
                <div class="value">{{ $site->id }}</div>
            </div>

            <div>
                <div class="label">Organization</div>
                <div class="value">{{ $site->organization->name ?? '-' }}</div>
            </div>

            <div>
                <div class="label">Incidents</div>
                <div class="value">{{ $site->incidents()->count() }}</div>
            </div>

            <div>
                <div class="label">Video Sources</div>
                <div class="value">{{ method_exists($site, 'nvrSystems') ? $site->nvrSystems()->count() : 0 }}</div>
            </div>

            <div>
                <div class="label">Time Zone</div>
                <div class="value">{{ $site->time_zone ?? '-' }}</div>
            </div>
        </div>
    </div>
@endsection
