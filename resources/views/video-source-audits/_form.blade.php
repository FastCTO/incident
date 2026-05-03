@php
    $audit = $audit ?? null;
    $selectedAuditType = old('audit_type', $audit->audit_type ?? 'initial_baseline');
    $selectedAuditStatus = old('audit_status', $audit->audit_status ?? 'pass');

    $source = $nvrSystem ?? $audit->videoSource ?? null;
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
            <label for="firmware_version">Firmware Version</label>
            <input type="text" name="firmware_version" id="firmware_version" value="{{ old('firmware_version', $audit->firmware_version ?? '') }}">
            @error('firmware_version') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="grid">
        <div class="field">
            <label for="software_version">Software Version</label>
            <input type="text" name="software_version" id="software_version" value="{{ old('software_version', $audit->software_version ?? '') }}">
            @error('software_version') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="os_version">OS / Platform Version</label>
            <input type="text" name="os_version" id="os_version" value="{{ old('os_version', $audit->os_version ?? '') }}">
            @error('os_version') <div class="error">{{ $message }}</div> @enderror
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

    <div class="field">
        <label for="mac_address_observed">MAC Address Observed</label>
        <input type="text" name="mac_address_observed" id="mac_address_observed" value="{{ old('mac_address_observed', $audit->mac_address_observed ?? '') }}">
        @error('mac_address_observed') <div class="error">{{ $message }}</div> @enderror
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
            <input type="text" name="system_time_zone" id="system_time_zone" value="{{ old('system_time_zone', $audit->system_time_zone ?? '') }}">
            @error('system_time_zone') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="field">
        <label for="time_drift_notes">Time Drift / NTP Notes</label>
        <textarea name="time_drift_notes" id="time_drift_notes" placeholder="Example: System clock matches actual time within 1 minute; NTP enabled">{{ old('time_drift_notes', $audit->time_drift_notes ?? '') }}</textarea>
        @error('time_drift_notes') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="card">
    <h2>Storage / Retention</h2>

    <div class="grid">
        <div class="field">
            <label for="total_storage">Total Storage</label>
            <input type="text" name="total_storage" id="total_storage" value="{{ old('total_storage', $audit->total_storage ?? '') }}" placeholder="Example: 8 TB">
            @error('total_storage') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="used_storage">Used Storage</label>
            <input type="text" name="used_storage" id="used_storage" value="{{ old('used_storage', $audit->used_storage ?? '') }}" placeholder="Example: 6.4 TB">
            @error('used_storage') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="grid">
        <div class="field">
            <label for="available_storage">Available Storage</label>
            <input type="text" name="available_storage" id="available_storage" value="{{ old('available_storage', $audit->available_storage ?? '') }}">
            @error('available_storage') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="storage_health">Storage Health</label>
            <input type="text" name="storage_health" id="storage_health" value="{{ old('storage_health', $audit->storage_health ?? '') }}" placeholder="Example: Healthy, RAID warning, disk failed">
            @error('storage_health') <div class="error">{{ $message }}</div> @enderror
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
            <label for="estimated_retention_days">Estimated Retention Days</label>
            <input type="number" min="0" name="estimated_retention_days" id="estimated_retention_days" value="{{ old('estimated_retention_days', $audit->estimated_retention_days ?? $source->estimated_retention_days ?? '') }}">
            @error('estimated_retention_days') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="field">
        <label for="recording_mode">Recording Mode</label>
        <input type="text" name="recording_mode" id="recording_mode" value="{{ old('recording_mode', $audit->recording_mode ?? '') }}" placeholder="Example: Continuous, motion, scheduled, mixed">
        @error('recording_mode') <div class="error">{{ $message }}</div> @enderror
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
            <label for="total_camera_count">Total Camera Count</label>
            <input type="number" min="0" name="total_camera_count" id="total_camera_count" value="{{ old('total_camera_count', $audit->total_camera_count ?? $source->camera_count ?? '') }}">
            @error('total_camera_count') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="active_camera_count">Active Camera Count</label>
            <input type="number" min="0" name="active_camera_count" id="active_camera_count" value="{{ old('active_camera_count', $audit->active_camera_count ?? '') }}">
            @error('active_camera_count') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="grid">
        <div class="field">
            <label for="offline_camera_count">Offline Camera Count</label>
            <input type="number" min="0" name="offline_camera_count" id="offline_camera_count" value="{{ old('offline_camera_count', $audit->offline_camera_count ?? '') }}">
            @error('offline_camera_count') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="disabled_camera_count">Disabled Camera Count</label>
            <input type="number" min="0" name="disabled_camera_count" id="disabled_camera_count" value="{{ old('disabled_camera_count', $audit->disabled_camera_count ?? '') }}">
            @error('disabled_camera_count') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="field">
        <label for="camera_view_notes">Camera View Notes</label>
        <textarea name="camera_view_notes" id="camera_view_notes" placeholder="Example: Front door camera view normal; back lot camera shifted left; register camera offline">{{ old('camera_view_notes', $audit->camera_view_notes ?? '') }}</textarea>
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
        <label for="unknown_user_count">Unknown User Count</label>
        <input type="number" min="0" name="unknown_user_count" id="unknown_user_count" value="{{ old('unknown_user_count', $audit->unknown_user_count ?? '') }}">
        @error('unknown_user_count') <div class="error">{{ $message }}</div> @enderror
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
