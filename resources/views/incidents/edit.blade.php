@extends('layouts.app')

@section('title', 'Edit Incident - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>Edit Incident</h1>
                <p>
                    <strong>Incident Reference:</strong> Incident #{{ $incident->id }}
                    <br>
                    Update incident details and manage attached files.
                </p>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <h2>Incident Details</h2>

        <div class="notice" style="margin-bottom: 20px;">
            <strong>Incident Reference:</strong> Incident #{{ $incident->id }}
            <br>
            This reference number is permanent. Use the title field below to add a readable name, like
            <strong>Incident #{{ $incident->id }} - Vic slip and fall</strong>.
        </div>

        <form method="POST" action="{{ route('incidents.update', $incident) }}">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="title">Incident Name / Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $incident->title) }}" required>
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="grid">
                <div class="field">
                    <label for="incident_type">Incident Type</label>
                    @php $selectedType = old('incident_type', $incident->incident_type); @endphp

                    <select name="incident_type" id="incident_type">
                        <option value="">Select type</option>
                        <option value="Theft" {{ $selectedType === 'Theft' ? 'selected' : '' }}>Theft</option>
                        <option value="Assault" {{ $selectedType === 'Assault' ? 'selected' : '' }}>Assault</option>
                        <option value="Vandalism" {{ $selectedType === 'Vandalism' ? 'selected' : '' }}>Vandalism</option>
                        <option value="Fire" {{ $selectedType === 'Fire' ? 'selected' : '' }}>Fire</option>
                        <option value="Slip and Fall" {{ $selectedType === 'Slip and Fall' ? 'selected' : '' }}>Slip and Fall</option>
                        <option value="Emergency Response" {{ $selectedType === 'Emergency Response' ? 'selected' : '' }}>Emergency Response</option>
                        <option value="Other" {{ $selectedType === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="field">
                    <label for="status">Status</label>
                    @php $selectedStatus = old('status', $incident->status); @endphp

                    <select name="status" id="status" required>
                        <option value="open" {{ $selectedStatus === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="reviewing" {{ $selectedStatus === 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                        <option value="evidence_requested" {{ $selectedStatus === 'evidence_requested' ? 'selected' : '' }}>Evidence Requested</option>
                        <option value="closed" {{ $selectedStatus === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
            </div>

            <div class="grid">
                <div class="field">
                    <label for="location_name">Location Name</label>
                    <input type="text" name="location_name" id="location_name" value="{{ old('location_name', $incident->location_name) }}">
                </div>

                <div class="field">
                    <label for="incident_datetime">Incident Date/Time</label>
                    <input
                        type="datetime-local"
                        name="incident_datetime"
                        id="incident_datetime"
                        value="{{ old('incident_datetime', $incident->incident_datetime ? $incident->incident_datetime->format('Y-m-d\TH:i') : '') }}"
                    >
                </div>
            </div>

            <div class="field">
                <label for="address">Address</label>
                <input type="text" name="address" id="address" value="{{ old('address', $incident->address) }}">
            </div>

            <div class="field">
                <label for="summary">Summary</label>
                <textarea name="summary" id="summary">{{ old('summary', $incident->summary) }}</textarea>
            </div>

            <div class="field">
                <label for="notes">Internal Notes</label>
                <textarea name="notes" id="notes">{{ old('notes', $incident->notes) }}</textarea>
            </div>

            <button type="submit" class="btn">Update Incident</button>
            <a href="{{ route('incidents.show', $incident) }}" class="btn btn-secondary">View Incident</a>
            <a href="{{ route('incidents.index') }}" class="btn btn-secondary">Back to Incidents</a>
        </form>
    </div>

    <div class="card">
        <h2>Manage Incident Files</h2>

        <div class="notice" style="margin-bottom: 20px;">
            Uploading a file will also save the current incident details above.
        </div>

        <form
            method="POST"
            action="{{ route('incidents.files.store', $incident) }}"
            enctype="multipart/form-data"
            id="file-upload-form"
        >
            @csrf

            <input type="hidden" name="incident_title" id="upload_incident_title">
            <input type="hidden" name="incident_type" id="upload_incident_type">
            <input type="hidden" name="incident_status" id="upload_incident_status">
            <input type="hidden" name="incident_location_name" id="upload_incident_location_name">
            <input type="hidden" name="incident_address" id="upload_incident_address">
            <input type="hidden" name="incident_datetime" id="upload_incident_datetime">
            <input type="hidden" name="incident_summary" id="upload_incident_summary">
            <input type="hidden" name="incident_notes" id="upload_incident_notes">

            <div class="field">
                <label for="file">Upload Image, Video, or Document</label>
                <input type="file" name="file" id="file" required>
                @error('file') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label for="file_notes">File Notes</label>
                <textarea name="notes" id="file_notes" placeholder="Example: Front door camera clip, 2:14 PM to 2:19 PM">{{ old('notes') }}</textarea>
                @error('notes') <div class="error">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn">Upload File</button>
        </form>

        <hr>

        @if($incident->files->count())
            <table>
                <thead>
                    <tr>
                        <th>File</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th>SHA-256</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($incident->files as $file)
                        <tr>
                            <td>
                                <a href="{{ $file->url }}" target="_blank">
                                    {{ $file->original_filename }}
                                </a>

                                @if($file->notes)
                                    <div style="font-size: 13px; color: #4b5563; margin-top: 5px;">
                                        {{ $file->notes }}
                                    </div>
                                @endif
                            </td>

                            <td>{{ ucfirst($file->file_type ?? 'other') }}</td>

                            <td>{{ $file->human_file_size }}</td>

                            <td style="font-family: monospace; font-size: 12px; word-break: break-all;">
                                {{ $file->sha256_hash ?? '-' }}
                            </td>

                            <td>
                                <form
                                    method="POST"
                                    action="{{ route('incidents.files.destroy', [$incident, $file]) }}"
                                    onsubmit="return confirm('Remove this file from the incident?');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="logout-button">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty">
                <h3>No files attached yet</h3>
                <p>Upload images, video clips, PDFs, or notes related to this incident.</p>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const uploadForm = document.getElementById('file-upload-form');

        if (!uploadForm) {
            return;
        }

        uploadForm.addEventListener('submit', function () {
            document.getElementById('upload_incident_title').value = document.getElementById('title').value;
            document.getElementById('upload_incident_type').value = document.getElementById('incident_type').value;
            document.getElementById('upload_incident_status').value = document.getElementById('status').value;
            document.getElementById('upload_incident_location_name').value = document.getElementById('location_name').value;
            document.getElementById('upload_incident_address').value = document.getElementById('address').value;
            document.getElementById('upload_incident_datetime').value = document.getElementById('incident_datetime').value;
            document.getElementById('upload_incident_summary').value = document.getElementById('summary').value;
            document.getElementById('upload_incident_notes').value = document.getElementById('notes').value;
        });
    });
</script>
@endpush
