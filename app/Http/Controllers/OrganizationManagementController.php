<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationManagementController extends Controller
{
    public function index()
    {
        $visibleOrganizationIds = $this->visibleOrganizationIds();

        $organizations = Organization::with(['parentOrganization', 'childOrganizations'])
            ->whereIn('id', $visibleOrganizationIds)
            ->orderByRaw("FIELD(organization_type, 'platform_owner', 'channel_partner', 'customer', 'site_account')")
            ->orderBy('name')
            ->paginate(25);

        return view('organizations.index', compact('organizations'));
    }

    public function create()
    {
        $parentOrganizations = $this->availableParentOrganizations();

        return view('organizations.create', compact('parentOrganizations'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateOrganization($request);

        if (!empty($validated['parent_organization_id'])) {
            $this->authorizeParentOrganization((int) $validated['parent_organization_id']);
        }

        $organization = Organization::create($validated);

        return redirect()
            ->route('organizations.edit', $organization)
            ->with('success', 'Organization created successfully.');
    }

    public function edit(Organization $organization)
    {
        $this->authorizeOrganizationAccess($organization);

        $organization->load(['parentOrganization', 'childOrganizations', 'users']);

        $parentOrganizations = $this->availableParentOrganizations()
            ->reject(fn ($possibleParent) => (int) $possibleParent->id === (int) $organization->id);

        return view('organizations.edit', compact('organization', 'parentOrganizations'));
    }

    public function update(Request $request, Organization $organization)
    {
        $this->authorizeOrganizationAccess($organization);

        $validated = $this->validateOrganization($request);

        if (!empty($validated['parent_organization_id'])) {
            if ((int) $validated['parent_organization_id'] === (int) $organization->id) {
                return redirect()
                    ->route('organizations.edit', $organization)
                    ->with('success', 'An organization cannot be its own parent.');
            }

            $this->authorizeParentOrganization((int) $validated['parent_organization_id']);
        }

        $organization->update($validated);

        return redirect()
            ->route('organizations.edit', $organization)
            ->with('success', 'Organization updated successfully.');
    }

    public function destroy(Organization $organization)
    {
        $this->authorizeOrganizationAccess($organization);

        if ($organization->childOrganizations()->count() > 0) {
            return redirect()
                ->route('organizations.index')
                ->with('success', 'Organization has child organizations and cannot be deleted.');
        }

        if ($organization->users()->count() > 0) {
            return redirect()
                ->route('organizations.index')
                ->with('success', 'Organization has users and cannot be deleted.');
        }

        if ($organization->sites()->count() > 0 || $organization->incidents()->count() > 0) {
            return redirect()
                ->route('organizations.index')
                ->with('success', 'Organization has sites or incidents and cannot be deleted.');
        }

        $organization->delete();

        return redirect()
            ->route('organizations.index')
            ->with('success', 'Organization deleted successfully.');
    }

    private function validateOrganization(Request $request): array
    {
        return $request->validate([
            'parent_organization_id' => ['nullable', 'integer', 'exists:organizations,id'],
            'name' => ['required', 'string', 'max:255'],
            'organization_type' => ['required', 'string', 'max:100'],
            'status' => ['required', 'string', 'max:100'],
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
    }

    private function currentOrganization(): ?Organization
    {
        return Auth::user()?->organization;
    }

    private function currentUserIsPlatformOwner(): bool
    {
        return $this->currentOrganization()?->organization_type === 'platform_owner';
    }

    private function currentUserIsChannelPartner(): bool
    {
        return $this->currentOrganization()?->organization_type === 'channel_partner';
    }

    private function visibleOrganizationIds(): array
    {
        $organization = $this->currentOrganization();

        if (!$organization) {
            return [];
        }

        if ($this->currentUserIsPlatformOwner()) {
            return Organization::pluck('id')->toArray();
        }

        if ($this->currentUserIsChannelPartner()) {
            return Organization::where('id', $organization->id)
                ->orWhere('parent_organization_id', $organization->id)
                ->pluck('id')
                ->toArray();
        }

        return [$organization->id];
    }

    private function availableParentOrganizations()
    {
        $organization = $this->currentOrganization();

        if (!$organization) {
            return collect();
        }

        if ($this->currentUserIsPlatformOwner()) {
            return Organization::whereIn('organization_type', ['platform_owner', 'channel_partner'])
                ->orderByRaw("FIELD(organization_type, 'platform_owner', 'channel_partner')")
                ->orderBy('name')
                ->get();
        }

        if ($this->currentUserIsChannelPartner()) {
            return Organization::where('id', $organization->id)
                ->orderBy('name')
                ->get();
        }

        return collect();
    }

    private function authorizeOrganizationAccess(Organization $organization): void
    {
        if (!in_array((int) $organization->id, array_map('intval', $this->visibleOrganizationIds()), true)) {
            abort(403, 'You do not have access to this organization.');
        }
    }

    private function authorizeParentOrganization(int $parentOrganizationId): void
    {
        $allowedParentIds = $this->availableParentOrganizations()
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        if (!in_array((int) $parentOrganizationId, $allowedParentIds, true)) {
            abort(403, 'You cannot assign this parent organization.');
        }
    }
}
