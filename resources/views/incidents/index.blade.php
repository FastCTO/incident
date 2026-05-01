<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FSV Incident - Incidents</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            color: #111827;
            margin: 0;
            padding: 30px;
        }

        .wrap {
            max-width: 1100px;
            margin: 0 auto;
        }

        .header {
            background: #ffffff;
            border-radius: 10px;
            padding: 24px;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
        }

        .header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        h1 {
            margin: 0;
            font-size: 28px;
        }

        p {
            color: #4b5563;
        }

        .btn {
            display: inline-block;
            background: #1d4ed8;
            color: white;
            padding: 10px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .card {
            background: #ffffff;
            border-radius: 10px;
            padding: 24px;
            border: 1px solid #e5e7eb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            border-bottom: 1px solid #e5e7eb;
            padding: 12px;
        }

        td {
            border-bottom: 1px solid #e5e7eb;
            padding: 12px;
        }

        a {
            color: #1d4ed8;
        }

        .empty {
            border: 2px dashed #d1d5db;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .logo {
            max-height: 70px;
            width: auto;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="header">
            <div class="header-row">
                <div>
                    <h1>FSV Incident</h1>
                    <p>Track incident reports, review status, and begin evidence workflows.</p>
                </div>

                <div>
                    <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="header-row" style="margin-bottom: 20px;">
                <h2 style="margin: 0;">Incidents</h2>
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
