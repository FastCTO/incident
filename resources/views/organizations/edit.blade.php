@extends('layouts.app')

@section('title', 'Edit Organization - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>Edit Organization</h1>
                <p>{{ $organization->name }}</p>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <h2>Organization Details</h2>

        <form method="POST" action="{{ route('organizations.update', $organization) }}">
            @csrf
            @method('PUT')

            @include('organizations._form', [
                'organization' => $organization,
                'parentOrganizations' => $parentOrganizations,
            ])

            <button type="submit" class="btn">Update Organization</button>
            <a href="{{ route('organizations.index') }}" class="btn btn-secondary">Back to Organizations</a>
        </form>
    </div>

    <div class="card">
        <h2>Hierarchy Summary</h2>

        <div class="details">
            <div>
                <div class="label">Organization ID</div>
                <div class="value">{{ $organization->id }}</div>
            </div>

            <div>
                <div class="label">Parent</div>
                <div class="value">{{ $organization->parentOrganization->name ?? '-' }}</div>
            </div>

            <div>
                <div class="label">Children</div>
                <div class="value">{{ $organization->childOrganizations()->count() }}</div>
            </div>

            <div>
                <div class="label">Users</div>
                <div class="value">{{ $organization->users()->count() }}</div>
            </div>

            <div>
                <div class="label">Sites</div>
                <div class="value">{{ $organization->sites()->count() }}</div>
            </div>

            <div>
                <div class="label">Incidents</div>
                <div class="value">{{ $organization->incidents()->count() }}</div>
            </div>
        </div>

        @if($organization->childOrganizations()->count())
            <h3>Child Organizations</h3>

            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Sites</th>
                        <th>Incidents</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($organization->childOrganizations as $child)
                        <tr>
                            <td>{{ $child->name }}</td>
                            <td>{{ $child->type_label }}</td>
                            <td>{{ $child->status_label }}</td>
                            <td>{{ $child->sites()->count() }}</td>
                            <td>{{ $child->incidents()->count() }}</td>
                            <td><a href="{{ route('organizations.edit', $child) }}">Edit</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
