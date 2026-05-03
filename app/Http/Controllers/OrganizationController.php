<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function edit()
    {
        $organization = $this->currentOrganization();

        return view('organization.edit', compact('organization'));
    }

    public function update(Request $request)
    {
        $organization = $this->currentOrganization();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'organization_type' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'max:100'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['organization_type'] = $validated['organization_type'] ?: $organization->organization_type;
        $validated['status'] = $validated['status'] ?: $organization->status;

        $organization->update($validated);

        return redirect()
            ->route('organization.edit')
            ->with('success', 'Organization profile updated successfully.');
    }

    private function currentOrganization(): Organization
    {
        $user = Auth::user();

        if ($user && $user->organization) {
            return $user->organization;
        }

        $organization = Organization::firstOrCreate(
            ['name' => 'Focus Secure Video'],
            [
                'organization_type' => 'platform_owner',
                'status' => 'active',
                'contact_name' => 'Vic Herrera',
                'contact_email' => 'vic@fsv.io',
                'country' => 'US',
                'notes' => 'Default organization created from organization profile controller.',
            ]
        );

        if ($user && !$user->organization_id) {
            $user->update([
                'organization_id' => $organization->id,
            ]);
        }

        return $organization;
    }
}
