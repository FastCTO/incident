@php
    $audit = $audit ?? null;
    $selectedAuditType = old('audit_type', $audit->audit_type ?? 'initial_baseline');
    $selectedAuditStatus = old('audit_status', $audit->audit_status ?? 'pass');
    $source = $nvrSystem ?? $audit->videoSource ?? null;

    $selectedTimeZone = old('system_time_zone', $audit->system_time_zone ?? $source->site->time_zone ?? 'America/Chicago');

    $timeZones = [
        'Pacific/Honolulu' => 'UTC-10 - Honolulu',
        'America/Anchorage' => 'UTC-09 - Anchorage',
        'America/Los_Angeles' => 'UTC-08/-07 - Los Angeles',
        'America/Denver' => 'UTC-07/-06 - Denver',
        'America/Chicago' => 'UTC-06/-05 - Chicago',
        'America/New_York' => 'UTC-05/-04 - New York',
        'America/Sao_Paulo' => 'UTC-03 - Sao Paulo',
        'Atlantic/Reykjavik' => 'UTC+00 - Reykjavik',
        'Europe/London' => 'UTC+00/+01 - London',
        'Europe/Berlin' => 'UTC+01/+02 - Berlin',
        'Europe/Athens' => 'UTC+02/+03 - Athens',
        'Europe/Moscow' => 'UTC+03 - Moscow',
        'Asia/Dubai' => 'UTC+04 - Dubai',
        'Asia/Karachi' => 'UTC+05 - Karachi',
        'Asia/Kolkata' => 'UTC+05:30 - New Delhi',
        'Asia/Dhaka' => 'UTC+06 - Dhaka',
        'Asia/Bangkok' => 'UTC+07 - Bangkok',
        'Asia/Singapore' => 'UTC+08 - Singapore',
        'Asia/Tokyo' => 'UTC+09 - Tokyo',
        'Australia/Sydney' => 'UTC+10/+11 - Sydney',
        'Pacific/Auckland' => 'UTC+12/+13 - Auckland',
    ];
@endphp

<div class="card">
    <h2>Audit Identity</h2>

    @if($source)
        <div class="notice" style="margin-bottom: 20px;">
            <strong>Video Source:</strong> {{ $source->name }}
            <br>
            <strong>Organization:</strong> {{ $source->organization->name ?? '-' }}
            <br>
            <strong>Site:</strong> {{ $source->site->name ?? '-' }}
        </div>
    @endif

    <div class="grid">
        <div class="field">
            <label for="audit_type">Audit Type</label>
            <select name="audit_type" id="audit_type" required>
                <option value="initial_baseline" {{ $selectedAuditType === 'initial_baseline' ? 'selected' : '' }}>Initial Baseline</option>
                <option value="quarterly" {{ $selectedAuditType === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                <option value="incident_driven" {{ $selectedAuditType === 'incident_driven' ? 'selected' : '' }}>Incident-Driven</option>
                <option value="maintenance" {{ $selectedAuditType === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                <option value="security_review" {{ $selectedAuditType === 'security_review' ? 'selected' : '' }}>Security Review</option>
            </select>
            @error('audit_type') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="audit_status">Audit Status</label>
            <select name="audit_status" id="audit_status" required>
                <option value="pass" {{ $selectedAuditStatus === 'pass' ? 'selected' : '' }}>Pass</option>
                <option value="warning" {{ $selectedAuditStatus === 'warning' ? 'selected' : '' }}>Warning</option>
                <option value="fail" {{ $selectedAuditStatus === 'fail' ? 'selected' : '' }}>Fail</option>
                <option value="needs_review" {{ $selectedAuditStatus === 'needs_review' ? 'selected' : '' }}>Needs Review</option>
            </select>
            @error('audit_status') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="field">
        <label for="performed_at">Performed At</label>
        <input
            type="datetime-local"
            name="performed_at"
            id="performed_at"
            value="{{ old('performed_at', $audit && $audit->performed_at ? $audit->performed_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
        >
        @error('performed_at') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="card">
    <h2>System Identity Observed</h2>

    <div class="grid">
        <div class="field">
            <label for="manufacturer_observed">Manufacturer Observed</label>
            <input type="text" name="manufacturer_observed" id="manufacturer_observed" value="{{ old('manufacturer_observed', $audit->manufacturer_observed ?? $source->manufacturer ?? '') }}">
            @error('manufacturer_observed') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="model_observed">Model Observed</label>
            <input type="text" name="model_observed" id="model_observed" value="{{ old('model_observed', $audit->model_observed ?? $source->model ?? '') }}">
            @error('model_observed') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="grid">
        <div class="field">
            <label for="serial_number_observed">Serial Number Observed</label>
            <input type="text" name="serial_number_observed" id="serial_number_observed" value="{{ old('serial_number_observed', $audit->serial_number_observed ?? $source->serial_number ?? '') }}">
            @error('serial_number_observed') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="installation_date">Installation Date, if known</label>
            <input
                type="date"
                name="installation_date"
                id="installation_date"
                value="{{ old('installation_date', $audit && $audit->installation_date ? $audit->installation_date->format('Y-m-d') : '') }}"
            >
            @error('installation_date') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="grid">
        <div class="field">
            <label for="firmware_version">Firmware Version</label>
            <input type="text" name="firmware_version" id="firmware_version" value="{{ old('firmware_version', $audit->firmware_version ?? '') }}">
            @error('firmware_version') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="software_version">Software Version</label>
            <input type="text" name="software_version" id="software_version" value="{{ old('software_version', $audit->software_version ?? '') }}">
            @error('software_version') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="grid">
        <div class="field">
            <label for="os_version">OS / Platform Version</label>
            <input type="text" name="os_version" id="os_version" value="{{ old('os_version', $audit->os_version ?? '') }}">
            @error('os_version') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="mac_address_observed">MAC Address Observed</label>
            <input type="text" name="mac_address_observed" id="mac_address_observed" value="{{ old('mac_address_observed', $audit->mac_address_observed ?? '') }}">
            @error('mac_address_observed') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="grid">
        <div class="field">
            <label for="hostname_observed">Hostname Observed</label>
            <input type="text" name="hostname_observed" id="hostname_observed" value="{{ old('hostname_observed', $audit->hostname_observed ?? $source->hostname ?? '') }}">
            @error('hostname_observed') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="source_ip_observed">Source IP Observed</label>
            <input type="text" name="source_ip_observed" id="source_ip_observed" value="{{ old('source_ip_observed', $audit->source_ip_observed ?? $source->ip_address ?? '') }}">
            @error('source_ip_observed') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="card">
    <h2>Time / Clock Check</h2>

    <div class="grid">
        <div class="field">
            <label for="system_datetime">System Date/Time Observed</label>
            <input
                type="datetime-local"
                name="system_datetime"
                id="system_datetime"
                value="{{ old('system_datetime', $audit && $audit->system_datetime ? $audit->system_datetime->format('Y-m-d\TH:i') : '') }}"
            >
            @error('system_datetime') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="system_time_zone">System Time Zone</label>
            <select name="system_time_zone" id="system_time_zone">
                <option value="">Unknown / Not visible</option>
                @foreach($timeZones as $zoneValue => $zoneLabel)
                    <option value="{{ $zoneValue }}" {{ $selectedTimeZone === $zoneValue ? 'selected' : '' }}>
                        {{ $zoneLabel }}
                    </option>
                @endforeach
            </select>
            @error('system_time_zone') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="field">
        <label for="ntp_enabled">NTP Enabled?</label>
        @php $selectedNtp = old('ntp_enabled', is_null($audit->ntp_enabled ?? null) ? '' : (int) $audit->ntp_enabled); @endphp
        <select name="ntp_enabled" id="ntp_enabled">
            <option value="" {{ $selectedNtp === '' ? 'selected' : '' }}>Unknown / Not visible</option>
            <option value="1" {{ (string) $selectedNtp === '1' ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ (string) $selectedNtp === '0' ? 'selected' : '' }}>No</option>
        </select>
        @error('ntp_enabled') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="time_drift_notes">Time Drift Notes</label>
        <textarea name="time_drift_notes" id="time_drift_notes" placeholder="Example: System clock matches actual time within 1 minute">{{ old('time_drift_notes', $audit->time_drift_notes ?? '') }}</textarea>
        @error('time_drift_notes') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="card">
    <h2>Storage / Retention</h2>

    <div class="grid">
        <div class="field">
            <label for="total_storage_amount">Total Storage</label>
            <input type="number" min="0" step="0.01" name="total_storage_amount" id="total_storage_amount" value="{{ old('total_storage_amount', $audit->total_storage_amount ?? '') }}" placeholder="Example: 8">
            @error('total_storage_amount') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="total_storage_unit">Storage Unit</label>
            @php $selectedStorageUnit = old('total_storage_unit', $audit->total_storage_unit ?? 'TB'); @endphp
            <select name="total_storage_unit" id="total_storage_unit">
                <option value="">Unknown</option>
                <option value="GB" {{ $selectedStorageUnit === 'GB' ? 'selected' : '' }}>GB</option>
                <option value="TB" {{ $selectedStorageUnit === 'TB' ? 'selected' : '' }}>TB</option>
            </select>
            @error('total_storage_unit') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="grid">
        <div class="field">
            <label for="used_storage">Used Storage / Recycling Status</label>
            <input type="text" name="used_storage" id="used_storage" value="{{ old('used_storage', $audit->used_storage ?? '') }}" placeholder="Example: Full / recycling normally">
            @error('used_storage') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="storage_status">Storage Status</label>
            @php $selectedStorageStatus = old('storage_status', $audit->storage_status ?? 'normal'); @endphp
            <select name="storage_status" id="storage_status">
                <option value="">Unknown / Not visible</option>
                <option value="normal" {{ $selectedStorageStatus === 'normal' ? 'selected' : '' }}>Normal / Recycling</option>
                <option value="warning" {{ $selectedStorageStatus === 'warning' ? 'selected' : '' }}>Warning observed</option>
                <option value="failure" {{ $selectedStorageStatus === 'failure' ? 'selected' : '' }}>Failure observed</option>
                <option value="not_visible" {{ $selectedStorageStatus === 'not_visible' ? 'selected' : '' }}>Not visible</option>
            </select>
            @error('storage_status') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="grid">
        <div class="field">
            <label for="oldest_recording_at">Oldest Recording Observed</label>
            <input
                type="datetime-local"
                name="oldest_recording_at"
                id="oldest_recording_at"
                value="{{ old('oldest_recording_at', $audit && $audit->oldest_recording_at ? $audit->oldest_recording_at->format('Y-m-d\TH:i') : '') }}"
            >
            @error('oldest_recording_at') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="oldest_recording_verified">Oldest Recording Verified?</label>
            @php $selectedOldestVerified = old('oldest_recording_verified', is_null($audit->oldest_recording_verified ?? null) ? '' : (int) $audit->oldest_recording_verified); @endphp
            <select name="oldest_recording_verified" id="oldest_recording_verified">
                <option value="" {{ $selectedOldestVerified === '' ? 'selected' : '' }}>Unknown / Not checked</option>
                <option value="1" {{ (string) $selectedOldestVerified === '1' ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ (string) $selectedOldestVerified === '0' ? 'selected' : '' }}>No</option>
            </select>
            @error('oldest_recording_verified') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="grid">
        <div class="field">
            <label for="estimated_retention_days">Estimated Retention Days</label>
            <input type="number" min="0" name="estimated_retention_days" id="estimated_retention_days" value="{{ old('estimated_retention_days', $audit->estimated_retention_days ?? $source->estimated_retention_days ?? '') }}">
            @error('estimated_retention_days') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="recording_mode">Recording Mode</label>
            @php $selectedRecordingMode = old('recording_mode', $audit->recording_mode ?? ''); @endphp
            <select name="recording_mode" id="recording_mode">
                <option value="">Unknown / Not visible</option>
                <option value="all_continuous_24_7" {{ $selectedRecordingMode === 'all_continuous_24_7' ? 'selected' : '' }}>All cameras continuous 24/7</option>
                <option value="hybrid_continuous_motion" {{ $selectedRecordingMode === 'hybrid_continuous_motion' ? 'selected' : '' }}>Hybrid: some continuous, some motion</option>
                <option value="all_motion" {{ $selectedRecordingMode === 'all_motion' ? 'selected' : '' }}>All cameras motion detect</option>
                <option value="scheduled" {{ $selectedRecordingMode === 'scheduled' ? 'selected' : '' }}>Scheduled recording</option>
                <option value="other" {{ $selectedRecordingMode === 'other' ? 'selected' : '' }}>Other / see notes</option>
            </select>
            @error('recording_mode') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="grid">
        <div class="field">
            <label for="export_test_performed">Export Test Performed?</label>
            @php $selectedExportPerformed = old('export_test_performed', is_null($audit->export_test_performed ?? null) ? '' : (int) $audit->export_test_performed); @endphp
            <select name="export_test_performed" id="export_test_performed">
                <option value="" {{ $selectedExportPerformed === '' ? 'selected' : '' }}>Unknown / Not checked</option>
                <option value="1" {{ (string) $selectedExportPerformed === '1' ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ (string) $selectedExportPerformed === '0' ? 'selected' : '' }}>No</option>
            </select>
            @error('export_test_performed') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="export_test_status">Export Test Status</label>
            @php $selectedExportStatus = old('export_test_status', $audit->export_test_status ?? ''); @endphp
            <select name="export_test_status" id="export_test_status">
                <option value="">Unknown / Not tested</option>
                <option value="pass" {{ $selectedExportStatus === 'pass' ? 'selected' : '' }}>Pass</option>
                <option value="warning" {{ $selectedExportStatus === 'warning' ? 'selected' : '' }}>Warning</option>
                <option value="fail" {{ $selectedExportStatus === 'fail' ? 'selected' : '' }}>Fail</option>
                <option value="blocked" {{ $selectedExportStatus === 'blocked' ? 'selected' : '' }}>Blocked / no access</option>
            </select>
            @error('export_test_status') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="field">
        <label for="export_test_notes">Export Test / Oldest Clip Notes</label>
        <textarea name="export_test_notes" id="export_test_notes" placeholder="Example: Exported 30-second sample from oldest visible footage. Clip attachment will be added separately.">{{ old('export_test_notes', $audit->export_test_notes ?? '') }}</textarea>
        @error('export_test_notes') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="retention_notes">Retention Notes</label>
        <textarea name="retention_notes" id="retention_notes">{{ old('retention_notes', $audit->retention_notes ?? '') }}</textarea>
        @error('retention_notes') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="card">
    <h2>Camera Inventory / Views</h2>

    <div class="grid">
        <div class="field">
            <label for="total_camera_count">Total Cameras</label>
            <input type="number" min="0" name="total_camera_count" id="total_camera_count" value="{{ old('total_camera_count', $audit->total_camera_count ?? $source->camera_count ?? '') }}">
            @error('total_camera_count') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="offline_camera_count">Offline Cameras</label>
            <input type="number" min="0" name="offline_camera_count" id="offline_camera_count" value="{{ old('offline_camera_count', $audit->offline_camera_count ?? '') }}">
            @error('offline_camera_count') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="field">
        <label for="camera_view_notes">Camera View Notes</label>
        <textarea name="camera_view_notes" id="camera_view_notes" placeholder="Example: Front door view normal; back lot camera shifted left; register camera offline">{{ old('camera_view_notes', $audit->camera_view_notes ?? '') }}</textarea>
        @error('camera_view_notes') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="card">
    <h2>User / Access / Security</h2>

    <div class="grid">
        <div class="field">
            <label for="admin_user_count">Admin User Count</label>
            <input type="number" min="0" name="admin_user_count" id="admin_user_count" value="{{ old('admin_user_count', $audit->admin_user_count ?? '') }}">
            @error('admin_user_count') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="standard_user_count">Standard User Count</label>
            <input type="number" min="0" name="standard_user_count" id="standard_user_count" value="{{ old('standard_user_count', $audit->standard_user_count ?? '') }}">
            @error('standard_user_count') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="field">
        <label for="last_login_notes">Last Login Notes</label>
        <textarea name="last_login_notes" id="last_login_notes">{{ old('last_login_notes', $audit->last_login_notes ?? '') }}</textarea>
        @error('last_login_notes') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="failed_login_notes">Failed Login Notes</label>
        <textarea name="failed_login_notes" id="failed_login_notes">{{ old('failed_login_notes', $audit->failed_login_notes ?? '') }}</textarea>
        @error('failed_login_notes') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="unusual_activity_notes">Unusual Activity Notes</label>
        <textarea name="unusual_activity_notes" id="unusual_activity_notes">{{ old('unusual_activity_notes', $audit->unusual_activity_notes ?? '') }}</textarea>
        @error('unusual_activity_notes') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="security_notes">Security Notes</label>
        <textarea name="security_notes" id="security_notes">{{ old('security_notes', $audit->security_notes ?? '') }}</textarea>
        @error('security_notes') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="card">
    <h2>Logs</h2>

    <div class="grid">
        <div class="field">
            <label for="logs_reviewed">Logs Reviewed?</label>
            @php $selectedLogsReviewed = old('logs_reviewed', is_null($audit->logs_reviewed ?? null) ? '' : (int) $audit->logs_reviewed); @endphp
            <select name="logs_reviewed" id="logs_reviewed">
                <option value="" {{ $selectedLogsReviewed === '' ? 'selected' : '' }}>Unknown / Not checked</option>
                <option value="1" {{ (string) $selectedLogsReviewed === '1' ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ (string) $selectedLogsReviewed === '0' ? 'selected' : '' }}>No</option>
            </select>
            @error('logs_reviewed') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="log_review_window">Log Review Window</label>
            <input type="text" name="log_review_window" id="log_review_window" value="{{ old('log_review_window', $audit->log_review_window ?? '') }}" placeholder="Example: Last 24 hours, last 7 days">
            @error('log_review_window') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="field">
        <label for="log_notes">Log Notes</label>
        <textarea name="log_notes" id="log_notes" placeholder="Example: No unusual failed logins observed. Export log file attachment will be added separately.">{{ old('log_notes', $audit->log_notes ?? '') }}</textarea>
        @error('log_notes') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="card">
    <h2>Summary / Next Steps</h2>

    <div class="field">
        <label for="overall_notes">Overall Notes</label>
        <textarea name="overall_notes" id="overall_notes">{{ old('overall_notes', $audit->overall_notes ?? '') }}</textarea>
        @error('overall_notes') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="recommended_actions">Recommended Actions</label>
        <textarea name="recommended_actions" id="recommended_actions">{{ old('recommended_actions', $audit->recommended_actions ?? '') }}</textarea>
        @error('recommended_actions') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="next_audit_due_at">Next Audit Due</label>
        <input
            type="datetime-local"
            name="next_audit_due_at"
            id="next_audit_due_at"
            value="{{ old('next_audit_due_at', $audit && $audit->next_audit_due_at ? $audit->next_audit_due_at->format('Y-m-d\TH:i') : '') }}"
        >
        @error('next_audit_due_at') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>
