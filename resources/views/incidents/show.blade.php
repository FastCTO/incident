@extends('layouts.app')

@section('title', $incident->title . ' - FSV Incident')

@section('content')
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
        <div>
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
                <h2>Attached Files</h2>

                @if($incident->files->count())
                    <table>
                        <thead>
                            <tr>
                                <th>File</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Uploaded At</th>
                                <th>Uploaded By</th>
                                <th>SHA-256</th>
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

                                    <td>{{ $file->created_at ? $file->created_at->format('M j, Y g:i A') : '-' }}</td>

                                    <td>{{ $file->uploader_display_name }}</td>

                                    <td style="font-family: monospace; font-size: 12px; word-break: break-all;">
                                        {{ $file->sha256_hash ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty">
                        <h3>No files attached yet</h3>
                        <p>Use Edit Incident to upload images, videos, PDFs, or notes.</p>
                    </div>
                @endif
            </div>
        </div>

        <div>
            <div class="card">
                <h2>Actions</h2>

                <p><a href="{{ route('incidents.edit', $incident) }}" class="btn">Edit Incident</a></p>
                <p><a href="{{ route('incidents.index') }}" class="btn btn-secondary">Back to Incidents</a></p>

                <div class="notice">
                    View mode is read-only. Uploads and file removal happen from Edit Incident.
                </div>

                <hr>

                <h3>Workflow</h3>
                <p><strong>1. Baseline</strong><br>Confirm system status.</p>
                <p><strong>2. Monitor</strong><br>Review footage and retention.</p>
                <p><strong>3. Incident</strong><br>Track the event and supporting details.</p>
                <p><strong>4. Evidence</strong><br>Package clips, hashes, notes, and access history.</p>
                <p><strong>5. Report</strong><br>Generate incident documentation.</p>
            </div>
        </div>
    </div>
@endsection
