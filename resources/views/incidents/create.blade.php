<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Incident - FSV Incident</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; color: #111827; margin: 0; padding: 30px; }
        .wrap { max-width: 900px; margin: 0 auto; }
        .card { background: #fff; border-radius: 10px; padding: 24px; border: 1px solid #e5e7eb; margin-bottom: 20px; }
        h1 { margin-top: 0; }
        label { display: block; font-weight: bold; margin-bottom: 6px; }
        input, select, textarea { width: 100%; box-sizing: border-box; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 15px; }
        textarea { min-height: 110px; }
        .field { margin-bottom: 18px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
        .btn { display: inline-block; background: #1d4ed8; color: white; padding: 10px 14px; border-radius: 6px; text-decoration: none; font-weight: bold; border: 0; cursor: pointer; font-size: 15px; }
        .btn-secondary { background: #e5e7eb; color: #111827; margin-left: 8px; }
        .error { color: #b91c1c; font-size: 14px; margin-top: 6px; }
        .logo { max-height: 70px; width: auto; }
        .header-row { display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        @media (max-width: 700px) { .grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <div class="header-row">
                <div>
                    <h1>Create Incident</h1>
                    <p>Start a new incident record.</p>
                </div>
                <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
            </div>
        </div>

        <div class="card">
            <form method="POST" action="{{ route('incidents.store') }}">
                @csrf

                <div class="field">
                    <label for="title">Incident Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required>
                    @error('title') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="grid">
                    <div class="field">
                        <label for="incident_type">Incident Type</label>
                        <select name="incident_type" id="incident_type">
                            <option value="">Select type</option>
                            <option value="Theft" {{ old('incident_type') === 'Theft' ? 'selected' : '' }}>Theft</option>
                            <option value="Assault" {{ old('incident_type') === 'Assault' ? 'selected' : '' }}>Assault</option>
                            <option value="Vandalism" {{ old('incident_type') === 'Vandalism' ? 'selected' : '' }}>Vandalism</option>
                            <option value="Fire" {{ old('incident_type') === 'Fire' ? 'selected' : '' }}>Fire</option>
                            <option value="Slip and Fall" {{ old('incident_type') === 'Slip and Fall' ? 'selected' : '' }}>Slip and Fall</option>
                            <option value="Emergency Response" {{ old('incident_type') === 'Emergency Response' ? 'selected' : '' }}>Emergency Response</option>
                            <option value="Other" {{ old('incident_type') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="status">Status</label>
                        <select name="status" id="status" required>
                            <option value="open" {{ old('status', 'open') === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="reviewing" {{ old('status') === 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                            <option value="evidence_requested" {{ old('status') === 'evidence_requested' ? 'selected' : '' }}>Evidence Requested</option>
                            <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                </div>

                <div class="grid">
                    <div class="field">
                        <label for="location_name">Location Name</label>
                        <input type="text" name="location_name" id="location_name" value="{{ old('location_name') }}">
                    </div>

                    <div class="field">
                        <label for="incident_datetime">Incident Date/Time</label>
                        <input type="datetime-local" name="incident_datetime" id="incident_datetime" value="{{ old('incident_datetime') }}">
                    </div>
                </div>

                <div class="field">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}">
                </div>

                <div class="field">
                    <label for="summary">Summary</label>
                    <textarea name="summary" id="summary">{{ old('summary') }}</textarea>
                </div>

                <div class="field">
                    <label for="notes">Internal Notes</label>
                    <textarea name="notes" id="notes">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" class="btn">Create Incident</button>
                <a href="{{ route('incidents.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
