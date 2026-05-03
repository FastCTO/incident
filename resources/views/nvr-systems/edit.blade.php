@extends('layouts.app')

@section('title', 'Edit Video Source - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>Edit Video Source</h1>
                <p>{{ $nvrSystem->name }}</p>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <h2>Video Source Details</h2>

        <form method="POST" action="{{ route('nvr-systems.update', $nvrSystem) }}">
            @csrf
            @method('PUT')

            @include('nvr-systems._form', ['nvrSystem' => $nvrSystem, 'sites' => $sites])

            <button type="submit" class="btn">Update Video Source</button>
            <a href="{{ route('nvr-systems.index') }}" class="btn btn-secondary">Back to Video Sources</a>
            <a href="{{ route('video-source-audits.create', $nvrSystem) }}" class="btn btn-secondary">Start New Audit</a>
        </form>
    </div>

    <div class="card">
        <h2>Source Trust Summary</h2>

        <div class="details">
            <div>
                <div class="label">Video Source ID</div>
                <div class="value">{{ $nvrSystem->id }}</div>
            </div>

            <div>
                <div class="label">Organization</div>
                <div class="value">{{ $nvrSystem->organization->name ?? '-' }}</div>
            </div>

            <div>
                <div class="label">Site</div>
                <div class="value">{{ $nvrSystem->site->name ?? '-' }}</div>
            </div>

            <div>
                <div class="label">Source</div>
                <div class="value">{{ $nvrSystem->system_label }}</div>
            </div>

            <div>
                <div class="label">Camera Count</div>
                <div class="value">{{ $nvrSystem->camera_count ?? '-' }}</div>
            </div>

            <div>
                <div class="label">Estimated Retention</div>
                <div class="value">{{ $nvrSystem->estimated_retention_days ? $nvrSystem->estimated_retention_days . ' days' : '-' }}</div>
            </div>

            <div>
                <div class="label">Last Checked</div>
                <div class="value">{{ $nvrSystem->last_checked_at ? $nvrSystem->last_checked_at->format('M j, Y g:i A') : '-' }}</div>
            </div>

            <div>
                <div class="label">Audit Count</div>
                <div class="value">{{ $nvrSystem->audits()->count() }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="header-row" style="margin-bottom: 20px;">
            <div>
                <h2>Audit History</h2>
                <p style="margin-top: -8px;">
                    Baseline and recurring checks for this video source. These help establish the trust chain.
                </p>
            </div>

            <a href="{{ route('video-source-audits.create', $nvrSystem) }}" class="btn">Start New Audit</a>
        </div>

        @if($nvrSystem->audits()->count())
            <table>
                <thead>
                    <tr>
                        <th>Performed At</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Performed By</th>
                        <th>IP</th>
                        <th>Retention</th>
                        <th>Cameras</th>
                        <th>Next Due</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nvrSystem->audits as $audit)
                        <tr>
                            <td>{{ $audit->performed_at ? $audit->performed_at->format('M j, Y g:i A') : '-' }}</td>
                            <td>{{ $audit->audit_type_label }}</td>
                            <td>{{ $audit->audit_status_label }}</td>
                            <td>{{ $audit->performer_display_name }}</td>
                            <td>{{ $audit->ip_address ?? '-' }}</td>
                            <td>{{ $audit->estimated_retention_days ? $audit->estimated_retention_days . ' days' : '-' }}</td>
                            <td>
                                {{ $audit->active_camera_count ?? '-' }} active
                                @if(!is_null($audit->offline_camera_count))
                                    <div style="font-size: 13px; color: #4b5563;">{{ $audit->offline_camera_count }} offline</div>
                                @endif
                            </td>
                            <td>{{ $audit->next_audit_due_at ? $audit->next_audit_due_at->format('M j, Y') : '-' }}</td>
                            <td>
                                <a href="{{ route('video-source-audits.edit', $audit) }}">Edit</a>
                                |
                                <form method="POST" action="{{ route('video-source-audits.destroy', $audit) }}" style="display: inline;" onsubmit="return confirm('Delete this audit record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="nav-button-link">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty">
                <h3>No audits yet</h3>
                <p>Start an initial baseline audit to begin building the trust chain for this video source.</p>
                <a href="{{ route('video-source-audits.create', $nvrSystem) }}" class="btn">Start Initial Audit</a>
            </div>
        @endif
    </div>
@endsection
