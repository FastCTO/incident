<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FSV Incident - Incidents</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('incidents._styles')
</head>
<body>
    <div class="wrap">
        @include('incidents._topnav')

        <div class="card">
            <div class="header-row">
                <div>
                    <h1>FSV Incident</h1>
                    <p>Track incident reports, review status, and begin evidence workflows.</p>
                </div>

                <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
            </div>
        </div>

        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="header-row" style="margin-bottom: 20px;">
                <h2>Incidents</h2>
                <a href="{{ route('incidents.create') }}" class="btn">New Incident</a>
            </div>

            @if($incidents->count())
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Location</th>
                            <th>Date/Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incidents as $incident)
                            <tr>
                                <td>
                                    <a href="{{ route('incidents.show', $incident) }}">
                                        {{ $incident->title }}
                                    </a>
                                </td>
                                <td>{{ $incident->incident_type ?? '-' }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $incident->status)) }}</td>
                                <td>{{ $incident->location_name ?? '-' }}</td>
                                <td>{{ $incident->incident_datetime ? $incident->incident_datetime->format('M j, Y g:i A') : '-' }}</td>
                                <td>
                                    <a href="{{ route('incidents.show', $incident) }}">View</a>
                                    |
                                    <a href="{{ route('incidents.edit', $incident) }}">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top: 20px;">
                    {{ $incidents->links() }}
                </div>
            @else
                <div class="empty">
                    <h3>No incidents yet</h3>
                    <p>Create the first incident to start building the evidence workflow.</p>
                    <a href="{{ route('incidents.create') }}" class="btn">Create Incident</a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
