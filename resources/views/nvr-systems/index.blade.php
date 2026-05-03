@extends('layouts.app')

@section('title', 'Video Sources - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>Video Sources</h1>
                <p>Document cameras, NVRs, DVRs, VMS platforms, and cloud video systems used as trusted evidence sources.</p>
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
                <h2>Video Sources</h2>
                <p style="margin-top: -8px;">Track individual cameras, recorders, and video platforms by site.</p>
            </div>

            <a href="{{ route('nvr-systems.create') }}" class="btn">Add Video Source</a>
        </div>

        @if($nvrSystems->count())
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Site</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Manufacturer / Model</th>
                        <th>IP / Hostname</th>
                        <th>Cameras</th>
                        <th>Retention</th>
                        <th>Last Checked</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nvrSystems as $nvrSystem)
                        <tr>
                            <td>
                                <a href="{{ route('nvr-systems.edit', $nvrSystem) }}">
                                    {{ $nvrSystem->name }}
                                </a>
                            </td>
                            <td>{{ $nvrSystem->site->name ?? '-' }}</td>
                            <td>{{ $nvrSystem->system_type ? ucwords(str_replace('_', ' ', $nvrSystem->system_type)) : '-' }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $nvrSystem->status)) }}</td>
                            <td>{{ $nvrSystem->system_label }}</td>
                            <td>
                                {{ $nvrSystem->ip_address ?? '-' }}
                                @if($nvrSystem->hostname)
                                    <div style="font-size: 13px; color: #4b5563;">{{ $nvrSystem->hostname }}</div>
                                @endif
                            </td>
                            <td>{{ $nvrSystem->camera_count ?? '-' }}</td>
                            <td>{{ $nvrSystem->estimated_retention_days ? $nvrSystem->estimated_retention_days . ' days' : '-' }}</td>
                            <td>{{ $nvrSystem->last_checked_at ? $nvrSystem->last_checked_at->format('M j, Y g:i A') : '-' }}</td>
                            <td>
                                <a href="{{ route('nvr-systems.edit', $nvrSystem) }}">Edit</a>
                                |
                                <form method="POST" action="{{ route('nvr-systems.destroy', $nvrSystem) }}" style="display: inline;" onsubmit="return confirm('Delete this video source profile?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="nav-button-link">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                {{ $nvrSystems->links() }}
            </div>
        @else
            <div class="empty">
                <h3>No video sources yet</h3>
                <p>Add the first camera, NVR, DVR, VMS, or cloud video source for this account.</p>
                <a href="{{ route('nvr-systems.create') }}" class="btn">Add Video Source</a>
            </div>
        @endif
    </div>
@endsection
