<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Organization;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationManagementController extends Controller
{
    public function index(Request $request)
    {
        $visibleOrganizationIds = $this->visibleOrganizationIds();

        $summary = [
            'total' => Organization::whereIn('id', $visibleOrganizationIds)->count(),

            'channel_partners' => Organization::whereIn('id', $visibleOrganizationIds)
                ->whereIn('organization_type', ['channel_partner', 'master_account'])
                ->count(),

            'customers' => Organization::whereIn('id', $visibleOrganizationIds)
                ->whereIn('organization_type', ['customer', 'site_account'])
                ->count(),
        ];

        $organizationsQuery = Organization::with(['parentOrganization'])
            ->withCount(['childOrganizations', 'users', 'sites', 'incidents'])
            ->whereIn('id', $visibleOrganizationIds);

        $type = $request->query('type');
        $parentOrganizationId = $request->query('parent_organization_id');

        if ($type) {
            if ($type === 'customer') {
                $organizationsQuery->whereIn('organization_type', ['customer', 'site_account']);
            } elseif ($type === 'channel_partner') {
                $organizationsQuery->whereIn('organization_type', ['channel_partner', 'master_account']);
            } elseif (in_array($type, ['platform_owner', 'master_account', 'site_account'], true)) {
                $organizationsQuery->where('organization_type', $type);
            }
        } elseif ($parentOrganizationId) {
            if (in_array((int) $parentOrganizationId, $visibleOrganizationIds, true)) {
                $organizationsQuery->where('parent_organization_id', (int) $parentOrganizationId);
            }
        } else {
            $organizationsQuery->whereIn('organization_type', [
                'platform_owner',
                'channel_partner',
                'master_account',
            ]);
        }

        $organizations = $organizationsQuery
            ->orderByRaw("FIELD(organization_type, 'platform_owner', 'master_account', 'channel_partner', 'customer', 'site_account')")
            ->orderBy('name')
            ->paginate(25)
            ->withQueryString();

        $organizations->getCollection()->transform(function ($organization) use ($visibleOrganizationIds) {
            $childOrganizationIds = Organization::where('parent_organization_id', $organization->id)
                ->whereIn('id', $visibleOrganizationIds)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->toArray();

            $rollupOrganizationIds = array_values(array_unique(array_merge(
                [(int) $organization->id],
                $childOrganizationIds
            )));

            $organization->customer_accounts_count = Organization::whereIn('id', $childOrganizationIds)
                ->whereIn('organization_type', ['customer', 'site_account'])
                ->count();

            $organization->rollup_sites_count = Site::whereIn('organization_id', $rollupOrganizationIds)->count();

            $organization->rollup_incidents_count = Incident::whereIn('organization_id', $rollupOrganizationIds)->count();

            return $organization;
        });

        return view('organizations.index', compact('organizations', 'summary', 'type', 'parentOrganizationId'));
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
                ->with('success', 'Organization has managed organizations and cannot be deleted.');
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
        $organizationId = Auth::user()?->organization_id;

        if (!$organizationId) {
            return null;
        }

        return Organization::find($organizationId);
    }

    private function currentUserIsPlatformOwner(): bool
    {
        return $this->currentOrganization()?->organization_type === 'platform_owner';
    }

    private function currentUserIsChannelPartner(): bool
    {
        return in_array($this->currentOrganization()?->organization_type, [
            'channel_partner',
            'master_account',
        ], true);
    }

    private function visibleOrganizationIds(): array
    {
        $organization = $this->currentOrganization();

        if (!$organization) {
            return [];
        }

        if ($this->currentUserIsPlatformOwner()) {
            return Organization::pluck('id')->map(fn ($id) => (int) $id)->toArray();
        }

        if ($this->currentUserIsChannelPartner()) {
            return Organization::where('id', $organization->id)
                ->orWhere('parent_organization_id', $organization->id)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->toArray();
        }

        return [(int) $organization->id];
    }

    private function availableParentOrganizations()
    {
        $organization = $this->currentOrganization();

        if (!$organization) {
            return collect();
        }

        if ($this->currentUserIsPlatformOwner()) {
            return Organization::whereIn('organization_type', ['platform_owner', 'master_account', 'channel_partner'])
                ->orderByRaw("FIELD(organization_type, 'platform_owner', 'master_account', 'channel_partner')")
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
        if ($this->currentUserIsPlatformOwner()) {
            return;
        }

        $visibleIds = $this->visibleOrganizationIds();

        if (!in_array((int) $organization->id, $visibleIds, true)) {
            abort(403, 'You do not have access to this organization.');
        }
    }

    private function authorizeParentOrganization(int $parentOrganizationId): void
    {
        if ($this->currentUserIsPlatformOwner()) {
            return;
        }

        $allowedParentIds = $this->availableParentOrganizations()
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        if (!in_array((int) $parentOrganizationId, $allowedParentIds, true)) {
            abort(403, 'You cannot assign this parent organization.');
        }
    }
}
