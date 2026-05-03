@php
    $organization = $organization ?? null;

    $selectedParentId = old('parent_organization_id', $organization->parent_organization_id ?? '');
    $selectedType = old('organization_type', $organization->organization_type ?? 'customer');
    $selectedStatus = old('status', $organization->status ?? 'active');
@endphp

<div class="field">
    <label for="name">Organization Name</label>
    <input
        type="text"
        name="name"
        id="name"
        value="{{ old('name', $organization->name ?? '') }}"
        required
    >
    @error('name') <div class="error">{{ $message }}</div> @enderror
</div>

<div class="grid">
    <div class="field">
        <label for="organization_type">Organization Type</label>
        <select name="organization_type" id="organization_type" required>
            <option value="platform_owner" {{ $selectedType === 'platform_owner' ? 'selected' : '' }}>Platform Owner</option>
            <option value="channel_partner" {{ $selectedType === 'channel_partner' ? 'selected' : '' }}>Channel Partner</option>
            <option value="customer" {{ $selectedType === 'customer' ? 'selected' : '' }}>Customer</option>
            <option value="site_account" {{ $selectedType === 'site_account' ? 'selected' : '' }}>Site Account</option>
        </select>
        @error('organization_type') <div class="error">{{ $message }}</div> @enderror
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

<div class="field">
    <label for="parent_organization_id">Parent Organization</label>
    <select name="parent_organization_id" id="parent_organization_id">
        <option value="">No parent / top level</option>

        @foreach($parentOrganizations as $parentOrganization)
            <option value="{{ $parentOrganization->id }}" {{ (string) $selectedParentId === (string) $parentOrganization->id ? 'selected' : '' }}>
                {{ $parentOrganization->name }} ({{ $parentOrganization->type_label }})
            </option>
        @endforeach
    </select>
    @error('parent_organization_id') <div class="error">{{ $message }}</div> @enderror

    <div style="font-size: 13px; color: #4b5563; margin-top: 6px;">
        Example: DTT and Eagle Eye should sit under FSV. Their customers should sit under them.
    </div>
</div>

<div class="grid">
    <div class="field">
        <label for="contact_name">Primary Contact Name</label>
        <input
            type="text"
            name="contact_name"
            id="contact_name"
            value="{{ old('contact_name', $organization->contact_name ?? '') }}"
        >
        @error('contact_name') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="contact_email">Primary Contact Email</label>
        <input
            type="email"
            name="contact_email"
            id="contact_email"
            value="{{ old('contact_email', $organization->contact_email ?? '') }}"
        >
        @error('contact_email') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="grid">
    <div class="field">
        <label for="contact_phone">Primary Contact Phone</label>
        <input
            type="text"
            name="contact_phone"
            id="contact_phone"
            value="{{ old('contact_phone', $organization->contact_phone ?? '') }}"
        >
        @error('contact_phone') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="website">Website</label>
        <input
            type="text"
            name="website"
            id="website"
            value="{{ old('website', $organization->website ?? '') }}"
            placeholder="https://example.com"
        >
        @error('website') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="field">
    <label for="address">Address</label>
    <input
        type="text"
        name="address"
        id="address"
        value="{{ old('address', $organization->address ?? '') }}"
    >
    @error('address') <div class="error">{{ $message }}</div> @enderror
</div>

<div class="grid">
    <div class="field">
        <label for="city">City</label>
        <input
            type="text"
            name="city"
            id="city"
            value="{{ old('city', $organization->city ?? '') }}"
        >
        @error('city') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="state">State</label>
        <input
            type="text"
            name="state"
            id="state"
            value="{{ old('state', $organization->state ?? '') }}"
        >
        @error('state') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="grid">
    <div class="field">
        <label for="postal_code">Postal Code</label>
        <input
            type="text"
            name="postal_code"
            id="postal_code"
            value="{{ old('postal_code', $organization->postal_code ?? '') }}"
        >
        @error('postal_code') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="country">Country</label>
        <input
            type="text"
            name="country"
            id="country"
            value="{{ old('country', $organization->country ?? 'US') }}"
        >
        @error('country') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="field">
    <label for="notes">Organization Notes</label>
    <textarea name="notes" id="notes">{{ old('notes', $organization->notes ?? '') }}</textarea>
    @error('notes') <div class="error">{{ $message }}</div> @enderror
</div>
