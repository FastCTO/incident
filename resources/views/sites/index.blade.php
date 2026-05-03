@extends('layouts.app')

@section('title', 'Sites - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>Sites</h1>
                <p>Manage physical locations for this account. Incidents, NVRs, cameras, and evidence will attach to sites.</p>
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
                <h2>{{ $organization->name ?? 'Account' }} Sites</h2>
                <p style="margin-top: -8px;">Sites represent customer locations, buildings, stores, campuses, or facilities.</p>
            </div>

            <a href="{{ route('sites.create') }}" class="btn">Add Site</a>
        </div>

        @if($sites->count())
            <table>
                <thead>
                    <tr>
                        <th>Site</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Incidents</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sites as $site)
                        <tr>
                            <td>
                                <a href="{{ route('sites.edit', $site) }}">{{ $site->name }}</a>
                            </td>
                            <td>{{ $site->site_type ? ucwords(str_replace('_', ' ', $site->site_type)) : '-' }}</td>
                            <td>{{ ucfirst($site->status) }}</td>
                            <td>
                                {{ $site->contact_name ?? '-' }}
                                @if($site->contact_email)
                                    <div style="font-size: 13px; color: #4b5563;">{{ $site->contact_email }}</div>
                                @endif
                            </td>
                            <td>{{ $site->full_address }}</td>
                            <td>{{ $site->incidents()->count() }}</td>
                            <td>
                                <a href="{{ route('sites.edit', $site) }}">Edit</a>

                                @if($site->incidents()->count() === 0)
                                    |
                                    <form method="POST" action="{{ route('sites.destroy', $site) }}" style="display: inline;" onsubmit="return confirm('Delete this site?');">
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
                {{ $sites->links() }}
            </div>
        @else
            <div class="empty">
                <h3>No sites yet</h3>
                <p>Add the first site for this account.</p>
                <a href="{{ route('sites.create') }}" class="btn">Add Site</a>
            </div>
        @endif
    </div>
@endsection
