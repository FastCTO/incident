@extends('layouts.app')

@section('title', 'Organizations - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>Organizations</h1>
                <p>Manage the account hierarchy for FSV, channel partners, and customer organizations.</p>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="header-row" style="margin-bottom: 20px;">
            <div>
                <h2>Organization Hierarchy</h2>
                <p style="margin-top: -8px;">
                    Platform owners can manage channel partners and customers. Channel partners can manage their customer accounts.
                </p>
            </div>

            <a href="{{ route('organizations.create') }}" class="btn">Add Organization</a>
        </div>

        @if($organizations->count())
            <table>
                <thead>
                    <tr>
                        <th>Organization</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Parent</th>
                        <th>Children</th>
                        <th>Users</th>
                        <th>Sites</th>
                        <th>Incidents</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($organizations as $organization)
                        <tr>
                            <td>
                                <a href="{{ route('organizations.edit', $organization) }}">
                                    {{ $organization->name }}
                                </a>
                            </td>

                            <td>{{ $organization->type_label }}</td>

                            <td>{{ $organization->status_label }}</td>

                            <td>{{ $organization->parentOrganization->name ?? '-' }}</td>

                            <td>{{ $organization->childOrganizations()->count() }}</td>

                            <td>{{ $organization->users()->count() }}</td>

                            <td>{{ $organization->sites()->count() }}</td>

                            <td>{{ $organization->incidents()->count() }}</td>

                            <td>
                                <a href="{{ route('organizations.edit', $organization) }}">Edit</a>

                                @if(
                                    $organization->childOrganizations()->count() === 0 &&
                                    $organization->users()->count() === 0 &&
                                    $organization->sites()->count() === 0 &&
                                    $organization->incidents()->count() === 0
                                )
                                    |
                                    <form method="POST" action="{{ route('organizations.destroy', $organization) }}" style="display: inline;" onsubmit="return confirm('Delete this organization?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="nav-button-link">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                {{ $organizations->links() }}
            </div>
        @else
            <div class="empty">
                <h3>No organizations found</h3>
                <p>Add the first organization to begin building the channel/customer hierarchy.</p>
                <a href="{{ route('organizations.create') }}" class="btn">Add Organization</a>
            </div>
        @endif
    </div>
@endsection
