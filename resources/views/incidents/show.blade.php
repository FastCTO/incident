<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $incident->title }} - FSV Incident</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('incidents._styles')
</head>
<body>
    <div class="wrap">
        @include('incidents._topnav')

        <div class="card">
            <div class="header-row">
                <div>
                    <h1>{{ $incident->title }}</h1>
                    <p>Incident record #{{ $incident->id }}</p>
                </div>

                <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
            </div>
        </div>

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <div class="detail-grid">
            <div class="card">
                <h2>Incident Summary</h2>

                <div class="details">
                    <div>
                        <div class="label">Type</div>
                        <div class="value">{{ $incident->incident_type ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="label">Status</div>
                        <div class="value">{{ ucfirst(str_replace('_', ' ', $incident->status)) }}</div>
                    </div>

                    <div>
                        <div class="label">Location</div>
                        <div class="value">{{ $incident->location_name ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="label">Date/Time</div>
                        <div class="value">{{ $incident->incident_datetime ? $incident->incident_datetime->format('M j, Y g:i A') : '-' }}</div>
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <div class="label">Address</div>
                        <div class="value">{{ $incident->address ?? '-' }}</div>
                    </div>
                </div>

                <h3>Summary</h3>
                <div class="box">{{ $incident->summary ?? 'No summary entered.' }}</div>

                <h3>Internal Notes</h3>
                <div class="box">{{ $incident->notes ?? 'No internal notes entered.' }}</div>
            </div>

            <div class="card">
                <h2>Actions</h2>

                <p><a href="{{ route('incidents.edit', $incident) }}" class="btn">Edit Incident</a></p>
                <p><a href="{{ route('incidents.index') }}" class="btn btn-secondary">Back to Incidents</a></p>

                <div class="notice">
                    Delete is intentionally hidden for now. We can add an admin-only delete or archive workflow later.
                </div>

                <hr>

                <h3>Workflow</h3>
                <p><strong>1. Baseline</strong><br>Confirm system status.</p>
                <p><strong>2. Monitor</strong><br>Review footage and retention.</p>
                <p><strong>3. Evidence</strong><br>Package clips, hashes, notes, and access history.</p>
                <p><strong>4. Report</strong><br>Generate incident documentation.</p>
            </div>
        </div>
    </div>
</body>
</html>
