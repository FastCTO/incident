@extends('layouts.app')

@section('title', 'Organization Profile - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>Organization Profile</h1>
                <p>Manage the account information used to group users, incidents, evidence, and future video source records.</p>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <h2>Account Details</h2>

        <form method="POST" action="{{ route('organization.update') }}">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="name">Organization / Account Name</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name', $organization->name) }}"
                    required
                >
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="grid">
                <div class="field">
                    <label for="organization_type">Organization Type</label>
                    @php $selectedType = old('organization_type', $organization->organization_type); @endphp

                    <select name="organization_type" id="organization_type">
                        <option value="platform_owner" {{ $selectedType === 'platform_owner' ? 'selected' : '' }}>Platform Owner</option>
                        <option value="channel_partner" {{ $selectedType === 'channel_partner' ? 'selected' : '' }}>Channel Partner</option>
                        <option value="customer" {{ $selectedType === 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="site_account" {{ $selectedType === 'site_account' ? 'selected' : '' }}>Site Account</option>
                    </select>
                    @error('organization_type') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="status">Status</label>
                    @php $selectedStatus = old('status', $organization->status); @endphp

                    <select name="status" id="status">
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
                    <label for="contact_name">Primary Contact Name</label>
                    <input
                        type="text"
                        name="contact_name"
                        id="contact_name"
                        value="{{ old('contact_name', $organization->contact_name) }}"
                    >
                    @error('contact_name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="contact_email">Primary Contact Email</label>
                    <input
                        type="email"
                        name="contact_email"
                        id="contact_email"
                        value="{{ old('contact_email', $organization->contact_email) }}"
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
                        value="{{ old('contact_phone', $organization->contact_phone) }}"
                    >
                    @error('contact_phone') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="website">Website</label>
                    <input
                        type="text"
                        name="website"
                        id="website"
                        value="{{ old('website', $organization->website) }}"
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
                    value="{{ old('address', $organization->address) }}"
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
                        value="{{ old('city', $organization->city) }}"
                    >
                    @error('city') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="state">State</label>
                    <input
                        type="text"
                        name="state"
                        id="state"
                        value="{{ old('state', $organization->state) }}"
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
                        value="{{ old('postal_code', $organization->postal_code) }}"
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
                <textarea name="notes" id="notes">{{ old('notes', $organization->notes) }}</textarea>
                @error('notes') <div class="error">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn">Update Organization</button>
            <a href="{{ route('incidents.index') }}" class="btn btn-secondary">Back to Incidents</a>
        </form>
    </div>

    <div class="card">
        <h2>Account Structure Preview</h2>

        <div class="details">
            <div>
                <div class="label">Organization ID</div>
                <div class="value">{{ $organization->id }}</div>
            </div>

            <div>
                <div class="label">Name</div>
                <div class="value">{{ $organization->name }}</div>
            </div>

            <div>
                <div class="label">Type</div>
                <div class="value">{{ ucwords(str_replace('_', ' ', $organization->organization_type)) }}</div>
            </div>

            <div>
                <div class="label">Status</div>
                <div class="value">{{ ucfirst($organization->status) }}</div>
            </div>

            <div>
                <div class="label">Users</div>
                <div class="value">{{ $organization->users()->count() }}</div>
            </div>

            <div>
                <div class="label">Incidents</div>
                <div class="value">{{ $organization->incidents()->count() }}</div>
            </div>
        </div>
    </div>
@endsection
