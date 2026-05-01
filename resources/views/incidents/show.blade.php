<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $incident->title }} - FSV Incident</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; color: #111827; margin: 0; padding: 30px; }
        .wrap { max-width: 1100px; margin: 0 auto; }
        .card { background: #fff; border-radius: 10px; padding: 24px; border: 1px solid #e5e7eb; margin-bottom: 20px; }
        .header-row { display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
        .details { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .label { font-size: 13px; color: #6b7280; font-weight: bold; text-transform: uppercase; }
        .value { margin-top: 4px; }
        .box { background: #f9fafb; border-radius: 8px; padding: 14px; white-space: pre-wrap; }
        .btn { display: inline-block; background: #1d4ed8; color: white; padding: 10px 14px; border-radius: 6px; text-decoration: none; font-weight: bold; border: 0; cursor: pointer; font-size: 15px; }
        .btn-secondary { background: #e5e7eb; color: #111827; }
        .btn-danger { background: #dc2626; width: 100%; margin-top: 12px; }
        .success { background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin-bottom: 20px; }
        .logo { max-height: 70px; width: auto; }
        @media (max-width: 850px) { .grid, .details { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="wrap">
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

        <div class="grid">
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

                <hr>

                <h3>Workflow</h3>
                <p><strong>1. Baseline</strong><br>Confirm system status.</p>
                <p><strong>2. Monitor</strong><br>Review footage and retention.</p>
                <p><strong>3. Evidence</strong><br>Package clips, hashes, notes, and access history.</p>
                <p><strong>4. Report</strong><br>Generate incident documentation.</p>

                <form method="POST" action="{{ route('incidents.destroy', $incident) }}" onsubmit="return confirm('Delete this incident? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Incident</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
