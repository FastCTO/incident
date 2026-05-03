@php
    $nvrSystem = $nvrSystem ?? null;
    $selectedSiteId = old('site_id', $nvrSystem->site_id ?? '');
    $selectedStatus = old('status', $nvrSystem->status ?? 'active');
    $selectedSystemType = old('system_type', $nvrSystem->system_type ?? '');
@endphp

<div class="grid">
    <div class="field">
        <label for="site_id">Site</label>
        <select name="site_id" id="site_id" required>
            <option value="">Select site</option>
            @foreach($sites as $site)
                <option value="{{ $site->id }}" {{ (string) $selectedSiteId === (string) $site->id ? 'selected' : '' }}>
                    {{ $site->name }}
                </option>
            @endforeach
        </select>
        @error('site_id') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="status">Status</label>
        <select name="status" id="status" required>
            <option value="active" {{ $selectedStatus === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ $selectedStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="needs_review" {{ $selectedStatus === 'needs_review' ? 'selected' : '' }}>Needs Review</option>
            <option value="archived" {{ $selectedStatus === 'archived' ? 'selected' : '' }}>Archived</option>
        </select>
        @error('status') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="field">
    <label for="name">System Name</label>
    <input
        type="text"
        name="name"
        id="name"
        value="{{ old('name', $nvrSystem->name ?? '') }}"
        placeholder="Example: Main Office Eagle Eye VMS"
        required
    >
    @error('name') <div class="error">{{ $message }}</div> @enderror
</div>

<div class="grid">
    <div class="field">
        <label for="system_type">System Type</label>
        <select name="system_type" id="system_type">
            <option value="">Select type</option>
            <option value="nvr" {{ $selectedSystemType === 'nvr' ? 'selected' : '' }}>NVR</option>
            <option value="dvr" {{ $selectedSystemType === 'dvr' ? 'selected' : '' }}>DVR</option>
            <option value="vms" {{ $selectedSystemType === 'vms' ? 'selected' : '' }}>VMS</option>
            <option value="cloud_vms" {{ $selectedSystemType === 'cloud_vms' ? 'selected' : '' }}>Cloud VMS</option>
            <option value="hybrid" {{ $selectedSystemType === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
            <option value="other" {{ $selectedSystemType === 'other' ? 'selected' : '' }}>Other</option>
        </select>
        @error('system_type') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="manufacturer">Manufacturer</label>
        <input type="text" name="manufacturer" id="manufacturer" value="{{ old('manufacturer', $nvrSystem->manufacturer ?? '') }}" placeholder="Example: Eagle Eye, Hikvision, Hanwha">
        @error('manufacturer') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="grid">
    <div class="field">
        <label for="model">Model</label>
        <input type="text" name="model" id="model" value="{{ old('model', $nvrSystem->model ?? '') }}">
        @error('model') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="serial_number">Serial Number</label>
        <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $nvrSystem->serial_number ?? '') }}">
        @error('serial_number') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="grid">
    <div class="field">
        <label for="hostname">Hostname</label>
        <input type="text" name="hostname" id="hostname" value="{{ old('hostname', $nvrSystem->hostname ?? '') }}">
        @error('hostname') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="ip_address">IP Address</label>
        <input type="text" name="ip_address" id="ip_address" value="{{ old('ip_address', $nvrSystem->ip_address ?? '') }}">
        @error('ip_address') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="grid">
    <div class="field">
        <label for="local_url">Local URL</label>
        <input type="text" name="local_url" id="local_url" value="{{ old('local_url', $nvrSystem->local_url ?? '') }}" placeholder="Example: http://192.168.1.50">
        @error('local_url') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="remote_url">Remote URL</label>
        <input type="text" name="remote_url" id="remote_url" value="{{ old('remote_url', $nvrSystem->remote_url ?? '') }}" placeholder="Example: https://vms.example.com">
        @error('remote_url') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="grid">
    <div class="field">
        <label for="camera_count">Camera Count</label>
        <input type="number" min="0" name="camera_count" id="camera_count" value="{{ old('camera_count', $nvrSystem->camera_count ?? '') }}">
        @error('camera_count') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="estimated_retention_days">Estimated Retention Days</label>
        <input type="number" min="0" name="estimated_retention_days" id="estimated_retention_days" value="{{ old('estimated_retention_days', $nvrSystem->estimated_retention_days ?? '') }}">
        @error('estimated_retention_days') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="field">
    <label for="last_checked_at">Last Checked At</label>
    <input
        type="datetime-local"
        name="last_checked_at"
        id="last_checked_at"
        value="{{ old('last_checked_at', $nvrSystem && $nvrSystem->last_checked_at ? $nvrSystem->last_checked_at->format('Y-m-d\TH:i') : '') }}"
    >
    @error('last_checked_at') <div class="error">{{ $message }}</div> @enderror
</div>

<div class="field">
    <label for="storage_notes">Storage / Retention Notes</label>
    <textarea name="storage_notes" id="storage_notes" placeholder="Example: 8TB RAID, oldest visible recording approximately 28 days">{{ old('storage_notes', $nvrSystem->storage_notes ?? '') }}</textarea>
    @error('storage_notes') <div class="error">{{ $message }}</div> @enderror
</div>

<div class="field">
    <label for="access_notes">Access Notes</label>
    <textarea name="access_notes" id="access_notes" placeholder="Example: Customer admin account available; export tested manually">{{ old('access_notes', $nvrSystem->access_notes ?? '') }}</textarea>
    @error('access_notes') <div class="error">{{ $message }}</div> @enderror
</div>

<div class="field">
    <label for="notes">General Notes</label>
    <textarea name="notes" id="notes">{{ old('notes', $nvrSystem->notes ?? '') }}</textarea>
    @error('notes') <div class="error">{{ $message }}</div> @enderror
</div>
