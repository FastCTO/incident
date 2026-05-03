@extends('layouts.app')

@section('title', 'View Video Source Audit - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>View Video Source Audit</h1>
                <p>{{ $audit->videoSource->name ?? 'Video Source' }} - {{ $audit->audit_type_label }}</p>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="notice" style="margin-bottom: 20px;">
        <strong>Append-only record:</strong>
        This audit is locked. Corrections or updates should be added as a future audit, addendum, or attachment record.
    </div>

    <div class="card">
        <h2>Captured Trust Chain Data</h2>

        <div class="details">
            <div>
                <div class="label">Performed By</div>
                <div class="value">{{ $audit->performer_display_name }}</div>
            </div>

            <div>
                <div class="label">Performed At</div>
                <div class="value">{{ $audit->performed_at ? $audit->performed_at->format('M j, Y g:i A') : '-' }}</div>
            </div>

            <div>
                <div class="label">IP Address</div>
                <div class="value">{{ $audit->ip_address ?? '-' }}</div>
            </div>

            <div>
                <div class="label">Request</div>
                <div class="value">{{ $audit->request_method ?? '-' }} {{ $audit->request_path ?? '-' }}</div>
            </div>

            <div style="grid-column: 1 / -1;">
                <div class="label">User Agent</div>
                <div class="value" style="font-family: monospace; font-size: 12px; word-break: break-all;">{{ $audit->user_agent ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Audit Summary</h2>

        <div class="details">
            <div>
                <div class="label">Audit Type</div>
                <div class="value">{{ $audit->audit_type_label }}</div>
            </div>

            <div>
                <div class="label">Audit Status</div>
                <div class="value">{{ $audit->audit_status_label }}</div>
            </div>

            <div>
                <div class="label">Organization</div>
                <div class="value">{{ $audit->organization->name ?? '-' }}</div>
            </div>

            <div>
                <div class="label">Site</div>
                <div class="value">{{ $audit->site->name ?? '-' }}</div>
            </div>

            <div>
                <div class="label">Video Source</div>
                <div class="value">{{ $audit->videoSource->name ?? '-' }}</div>
            </div>

            <div>
                <div class="label">Next Audit Due</div>
                <div class="value">{{ $audit->next_audit_due_at ? $audit->next_audit_due_at->format('M j, Y g:i A') : '-' }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>System Identity Observed</h2>

        <div class="details">
            <div><div class="label">Manufacturer</div><div class="value">{{ $audit->manufacturer_observed ?? '-' }}</div></div>
            <div><div class="label">Model</div><div class="value">{{ $audit->model_observed ?? '-' }}</div></div>
            <div><div class="label">Serial Number</div><div class="value">{{ $audit->serial_number_observed ?? '-' }}</div></div>
            <div><div class="label">Installation Date</div><div class="value">{{ $audit->installation_date ? $audit->installation_date->format('M j, Y') : '-' }}</div></div>
            <div><div class="label">Firmware</div><div class="value">{{ $audit->firmware_version ?? '-' }}</div></div>
            <div><div class="label">Software</div><div class="value">{{ $audit->software_version ?? '-' }}</div></div>
            <div><div class="label">OS / Platform</div><div class="value">{{ $audit->os_version ?? '-' }}</div></div>
            <div><div class="label">MAC Address</div><div class="value">{{ $audit->mac_address_observed ?? '-' }}</div></div>
            <div><div class="label">Hostname</div><div class="value">{{ $audit->hostname_observed ?? '-' }}</div></div>
            <div><div class="label">Source IP</div><div class="value">{{ $audit->source_ip_observed ?? '-' }}</div></div>
        </div>
    </div>

    <div class="card">
        <h2>Time / Clock Check</h2>

        <div class="details">
            <div><div class="label">System Date/Time</div><div class="value">{{ $audit->system_datetime ? $audit->system_datetime->format('M j, Y g:i A') : '-' }}</div></div>
            <div><div class="label">System Time Zone</div><div class="value">{{ $audit->system_time_zone ?? '-' }}</div></div>
            <div><div class="label">NTP Enabled</div><div class="value">{{ $audit->ntp_enabled_label }}</div></div>
        </div>

        <h3>Time Drift Notes</h3>
        <div class="box">{{ $audit->time_drift_notes ?? 'No time drift notes entered.' }}</div>
    </div>

    <div class="card">
        <h2>Storage / Retention</h2>

        <div class="details">
            <div><div class="label">Total Storage</div><div class="value">{{ $audit->total_storage_amount ? $audit->total_storage_amount . ' ' . ($audit->total_storage_unit ?? '') : '-' }}</div></div>
            <div><div class="label">Used Storage</div><div class="value">{{ $audit->used_storage ?? '-' }}</div></div>
            <div><div class="label">Storage Status</div><div class="value">{{ $audit->storage_status ? ucwords(str_replace('_', ' ', $audit->storage_status)) : '-' }}</div></div>
            <div><div class="label">Oldest Recording</div><div class="value">{{ $audit->oldest_recording_at ? $audit->oldest_recording_at->format('M j, Y g:i A') : '-' }}</div></div>
            <div><div class="label">Oldest Recording Verified</div><div class="value">{{ $audit->oldest_recording_verified_label }}</div></div>
            <div><div class="label">Estimated Retention</div><div class="value">{{ $audit->estimated_retention_days ? $audit->estimated_retention_days . ' days' : '-' }}</div></div>
            <div><div class="label">Recording Mode</div><div class="value">{{ $audit->recording_mode ? ucwords(str_replace('_', ' ', $audit->recording_mode)) : '-' }}</div></div>
            <div><div class="label">Export Test Performed</div><div class="value">{{ $audit->export_test_performed_label }}</div></div>
            <div><div class="label">Export Test Status</div><div class="value">{{ $audit->export_test_status ? ucwords(str_replace('_', ' ', $audit->export_test_status)) : '-' }}</div></div>
        </div>

        <h3>Export Test Notes</h3>
        <div class="box">{{ $audit->export_test_notes ?? 'No export test notes entered.' }}</div>

        <h3>Retention Notes</h3>
        <div class="box">{{ $audit->retention_notes ?? 'No retention notes entered.' }}</div>
    </div>

    <div class="card">
        <h2>Camera Inventory / Views</h2>

        <div class="details">
            <div><div class="label">Total Cameras</div><div class="value">{{ $audit->total_camera_count ?? '-' }}</div></div>
            <div><div class="label">Offline Cameras</div><div class="value">{{ $audit->offline_camera_count ?? '-' }}</div></div>
        </div>

        <h3>Camera View Notes</h3>
        <div class="box">{{ $audit->camera_view_notes ?? 'No camera view notes entered.' }}</div>
    </div>

    <div class="card">
        <h2>User / Access / Security</h2>

        <div class="details">
            <div><div class="label">Admin Users</div><div class="value">{{ $audit->admin_user_count ?? '-' }}</div></div>
            <div><div class="label">Standard Users</div><div class="value">{{ $audit->standard_user_count ?? '-' }}</div></div>
        </div>

        <h3>Last Login Notes</h3>
        <div class="box">{{ $audit->last_login_notes ?? 'No last login notes entered.' }}</div>

        <h3>Failed Login Notes</h3>
        <div class="box">{{ $audit->failed_login_notes ?? 'No failed login notes entered.' }}</div>

        <h3>Unusual Activity Notes</h3>
        <div class="box">{{ $audit->unusual_activity_notes ?? 'No unusual activity notes entered.' }}</div>

        <h3>Security Notes</h3>
        <div class="box">{{ $audit->security_notes ?? 'No security notes entered.' }}</div>
    </div>

    <div class="card">
        <h2>Logs</h2>

        <div class="details">
            <div><div class="label">Logs Reviewed</div><div class="value">{{ $audit->logs_reviewed_label }}</div></div>
            <div><div class="label">Log Review Window</div><div class="value">{{ $audit->log_review_window ?? '-' }}</div></div>
        </div>

        <h3>Log Notes</h3>
        <div class="box">{{ $audit->log_notes ?? 'No log notes entered.' }}</div>
    </div>

    <div class="card">
        <h2>Summary / Next Steps</h2>

        <h3>Overall Notes</h3>
        <div class="box">{{ $audit->overall_notes ?? 'No overall notes entered.' }}</div>

        <h3>Recommended Actions</h3>
        <div class="box">{{ $audit->recommended_actions ?? 'No recommended actions entered.' }}</div>

        <p style="margin-top: 20px;">
            <a href="{{ route('nvr-systems.edit', $audit->videoSource) }}" class="btn">Back to Video Source</a>
        </p>
    </div>
@endsection
