@php
    $site = $site ?? null;
    $selectedOrganizationId = old('organization_id', $site->organization_id ?? '');
    $selectedStatus = old('status', $site->status ?? 'active');
@endphp

<div class="field">
    <label for="organization_id">Organization / Account</label>
    <select name="organization_id" id="organization_id" required>
        <option value="">Select organization</option>

        @foreach($organizations as $organization)
            <option value="{{ $organization->id }}" {{ (string) $selectedOrganizationId === (string) $organization->id ? 'selected' : '' }}>
                {{ $organization->name }} ({{ ucwords(str_replace('_', ' ', $organization->organization_type)) }})
            </option>
        @endforeach
    </select>
    @error('organization_id') <div class="error">{{ $message }}</div> @enderror
</div>

<div class="field">
    <label for="name">Site Name</label>
    <input type="text" name="name" id="name" value="{{ old('name', $site->name ?? '') }}" required>
    @error('name') <div class="error">{{ $message }}</div> @enderror
</div>

<div class="grid">
    <div class="field">
        <label for="site_type">Site Type</label>
        <input
            type="text"
            name="site_type"
            id="site_type"
            value="{{ old('site_type', $site->site_type ?? '') }}"
            placeholder="Example: Store, Campus, Warehouse, Office"
        >
        @error('site_type') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="status">Status</label>
        <select name="status" id="status" required>
            <option value="active" {{ $selectedStatus === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ $selectedStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="prospect" {{ $selectedStatus === 'prospect' ? 'selected' : '' }}>Prospect</option>
            <option value="archived" {{ $selectedStatus === 'archived' ? 'selected' : '' }}>Archived</option>
        </select>
        @error('status') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="grid">
    <div class="field">
        <label for="contact_name">Site Contact Name</label>
        <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name', $site->contact_name ?? '') }}">
        @error('contact_name') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="contact_email">Site Contact Email</label>
        <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $site->contact_email ?? '') }}">
        @error('contact_email') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="grid">
    <div class="field">
        <label for="contact_phone">Site Contact Phone</label>
        <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $site->contact_phone ?? '') }}">
        @error('contact_phone') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="time_zone">Time Zone</label>
        <input type="text" name="time_zone" id="time_zone" value="{{ old('time_zone', $site->time_zone ?? 'America/Chicago') }}">
        @error('time_zone') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="field">
    <label for="address">Address</label>
    <input type="text" name="address" id="address" value="{{ old('address', $site->address ?? '') }}">
    @error('address') <div class="error">{{ $message }}</div> @enderror
</div>

<div class="grid">
    <div class="field">
        <label for="city">City</label>
        <input type="text" name="city" id="city" value="{{ old('city', $site->city ?? '') }}">
        @error('city') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="state">State</label>
        <input type="text" name="state" id="state" value="{{ old('state', $site->state ?? '') }}">
        @error('state') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="grid">
    <div class="field">
        <label for="postal_code">Postal Code</label>
        <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $site->postal_code ?? '') }}">
        @error('postal_code') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="country">Country</label>
        <input type="text" name="country" id="country" value="{{ old('country', $site->country ?? 'US') }}">
        @error('country') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="field">
    <label for="notes">Site Notes</label>
    <textarea name="notes" id="notes">{{ old('notes', $site->notes ?? '') }}</textarea>
    @error('notes') <div class="error">{{ $message }}</div> @enderror
</div>
