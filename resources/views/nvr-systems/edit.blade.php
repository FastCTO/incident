@extends('layouts.app')

@section('title', 'Edit NVR/VMS - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>Edit NVR/VMS</h1>
                <p>{{ $nvrSystem->name }}</p>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <h2>System Details</h2>

        <form method="POST" action="{{ route('nvr-systems.update', $nvrSystem) }}">
            @csrf
            @method('PUT')

            @include('nvr-systems._form', ['nvrSystem' => $nvrSystem, 'sites' => $sites])

            <button type="submit" class="btn">Update NVR/VMS</button>
            <a href="{{ route('nvr-systems.index') }}" class="btn btn-secondary">Back to NVR/VMS</a>
        </form>
    </div>

    <div class="card">
        <h2>System Summary</h2>

        <div class="details">
            <div>
                <div class="label">NVR/VMS ID</div>
                <div class="value">{{ $nvrSystem->id }}</div>
            </div>

            <div>
                <div class="label">Organization</div>
                <div class="value">{{ $nvrSystem->organization->name ?? '-' }}</div>
            </div>

            <div>
                <div class="label">Site</div>
                <div class="value">{{ $nvrSystem->site->name ?? '-' }}</div>
            </div>

            <div>
                <div class="label">System</div>
                <div class="value">{{ $nvrSystem->system_label }}</div>
            </div>

            <div>
                <div class="label">Camera Count</div>
                <div class="value">{{ $nvrSystem->camera_count ?? '-' }}</div>
            </div>

            <div>
                <div class="label">Estimated Retention</div>
                <div class="value">{{ $nvrSystem->estimated_retention_days ? $nvrSystem->estimated_retention_days . ' days' : '-' }}</div>
            </div>
        </div>
    </div>
@endsection
