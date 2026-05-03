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

    @if($incident->archived_at)
        <div class="notice" style="margin-bottom: 20px;">
            <strong>Archived Incident:</strong>
            This incident was archived on {{ $incident->archived_at->format('M j, Y g:i A') }}.

            @if($incident->archivedBy)
                Archived by {{ $incident->archivedBy->name ?? $incident->archivedBy->email }}.
            @endif
        </div>
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

            <div class="card">
                <h2>Event Timeline</h2>

                <p style="margin-top: -8px;">
                    Chain-of-custody activity for this incident, including file uploads, updates, request data, and hash metadata.
                </p>

                @if($incident->events->count())
                    <table>
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Actor</th>
                                <th>Event</th>
                                <th>Description</th>
                                <th>IP</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($incident->events as $event)
                                @php
                                    $eventLabel = ucwords(str_replace('_', ' ', $event->event_type));
                                    $metadataPretty = $event->metadata
                                        ? json_encode($event->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                                        : null;
                                @endphp

                                <tr>
                                    <td style="white-space: nowrap;">
                                        {{ $event->created_at ? $event->created_at->format('M j, Y g:i A') : '-' }}
                                    </td>

                                    <td>{{ $event->actor_display_name }}</td>

                                    <td>{{ $eventLabel }}</td>

                                    <td>{{ $event->description ?? '-' }}</td>

                                    <td style="white-space: nowrap;">
                                        {{ $event->ip_address ?? '-' }}
                                    </td>

                                    <td>
                                        <details>
                                            <summary style="cursor: pointer; color: #1d4ed8; font-weight: 600;">
                                                Details
                                            </summary>

                                            <div style="margin-top: 10px; font-size: 13px;">
                                                <div style="margin-bottom: 8px;">
                                                    <strong>Event ID:</strong> {{ $event->id }}
                                                </div>

                                                <div style="margin-bottom: 8px;">
                                                    <strong>Event Type:</strong> {{ $event->event_type }}
                                                </div>

                                                <div style="margin-bottom: 8px;">
                                                    <strong>User ID:</strong> {{ $event->user_id ?? '-' }}
                                                </div>

                                                <div style="margin-bottom: 8px;">
                                                    <strong>IP Address:</strong> {{ $event->ip_address ?? '-' }}
                                                </div>

                                                <div style="margin-bottom: 8px;">
                                                    <strong>Request:</strong>
                                                    {{ $event->request_method ?? '-' }}
                                                    {{ $event->request_path ?? '-' }}
                                                </div>

                                                <div style="margin-bottom: 8px;">
                                                    <strong>User Agent:</strong>
                                                    <div style="font-family: monospace; word-break: break-all; margin-top: 4px;">
                                                        {{ $event->user_agent ?? '-' }}
                                                    </div>
                                                </div>

                                                <div style="margin-bottom: 8px;">
                                                    <strong>Created At:</strong>
                                                    {{ $event->created_at ? $event->created_at->format('M j, Y g:i:s A') : '-' }}
                                                </div>

                                                <div style="margin-bottom: 8px;">
                                                    <strong>Updated At:</strong>
                                                    {{ $event->updated_at ? $event->updated_at->format('M j, Y g:i:s A') : '-' }}
                                                </div>

                                                <div>
                                                    <strong>Metadata:</strong>

                                                    @if($metadataPretty)
                                                        <pre style="white-space: pre-wrap; word-break: break-word; background: #f3f4f6; padding: 10px; border-radius: 6px; margin-top: 6px; font-size: 12px;">{{ $metadataPretty }}</pre>
                                                    @else
                                                        <div style="margin-top: 4px;">-</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </details>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty">
                        <h3>No timeline events yet</h3>
                        <p>As incidents are created, updated, and files are handled, chain-of-custody events will appear here.</p>
                    </div>
                @endif
            </div>
        </div>

        <div>
            <div class="card">
                <h2>Actions</h2>

                @if(!$incident->archived_at)
                    <p><a href="{{ route('incidents.edit', $incident) }}" class="btn">Edit Incident</a></p>

                    <form
                        method="POST"
                        action="{{ route('incidents.destroy', $incident) }}"
                        onsubmit="return confirm('Archive this incident? It will be hidden from the active incident list but retained for evidence history.');"
                        style="margin-bottom: 16px;"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary">Archive Incident</button>
                    </form>
                @else
                    <div class="notice">
                        This incident is archived. Editing is disabled for now.
                    </div>
                @endif

                <p><a href="{{ route('incidents.index') }}" class="btn btn-secondary">Back to Active Incidents</a></p>
                <p><a href="{{ route('incidents.index', ['archived' => 1]) }}" class="btn btn-secondary">View Archived Incidents</a></p>

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

            <div class="card">
                <h2>Evidence Integrity Notes</h2>

                <p>
                    This incident record now tracks uploaded files, SHA-256 hashes, uploader identity,
                    upload time, request source, and event history.
                </p>

                <p>
                    Future integrity checks should recalculate file hashes and log pass/fail results
                    as additional timeline events.
                </p>
            </div>
        </div>
    </div>
@endsection
