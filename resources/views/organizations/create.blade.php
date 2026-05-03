@extends('layouts.app')

@section('title', 'Add Organization - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>Add Organization</h1>
                <p>Create a channel partner, customer account, or child organization.</p>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    <div class="card">
        <h2>Organization Details</h2>

        <form method="POST" action="{{ route('organizations.store') }}">
            @csrf

            @include('organizations._form', [
                'organization' => null,
                'parentOrganizations' => $parentOrganizations,
            ])

            <button type="submit" class="btn">Create Organization</button>
            <a href="{{ route('organizations.index') }}" class="btn btn-secondary">Back to Organizations</a>
        </form>
    </div>
@endsection
