<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\NvrSystem;
use App\Models\Organization;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteController extends Controller
{
    public function index()
    {
        $organizationIds = $this->visibleOrganizationIds();

        $sites = Site::with('organization')
            ->withCount(['nvrSystems', 'incidents'])
            ->whereIn('organization_id', $organizationIds)
            ->orderBy('organization_id')
            ->orderBy('name')
            ->paginate(20);

        $organization = $this->currentOrganization();
        $showOrganizationColumn = count($organizationIds) > 1;

        $summary = [
            'customers' => Organization::whereIn('id', $organizationIds)
                ->whereIn('organization_type', ['customer', 'site_account'])
                ->count(),

            'sites' => Site::whereIn('organization_id', $organizationIds)
                ->count(),

            'video_sources' => NvrSystem::whereIn('organization_id', $organizationIds)
                ->count(),

            'incidents' => Incident::whereIn('organization_id', $organizationIds)
                ->count(),

            'cameras' => (int) NvrSystem::whereIn('organization_id', $organizationIds)
                ->sum('camera_count'),

            'needs_audit' => NvrSystem::whereIn('organization_id', $organizationIds)
                ->where(function ($query) {
                    $query->whereNull('last_checked_at')
                        ->orWhere('last_checked_at', '<', now()->subDays(90));
                })
                ->count(),
        ];

        return view('sites.index', compact(
            'sites',
            'organization',
            'showOrganizationColumn',
            'summary'
        ));
    }

    public function create()
    {
        $organizations = $this->availableOrganizationsForSiteAssignment();

        return view('sites.create', compact('organizations'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateSite($request);

        if (empty($validated['organization_id'])) {
            $validated['organization_id'] = $this->currentOrganization()?->id;
        }

        $this->authorizeOrganizationForSite((int) $validated['organization_id']);

        $site = Site::create($validated);

        return redirect()
            ->route('sites.edit', $site)
            ->with('success', 'Site created successfully.');
    }

    public function edit(Site $site)
    {
        $this->authorizeSiteAccess($site);

        $organizations = $this->availableOrganizationsForSiteAssignment();

        return view('sites.edit', compact('site', 'organizations'));
    }

    public function update(Request $request, Site $site)
    {
        $this->authorizeSiteAccess($site);

        $validated = $this->validateSite($request);

        if (empty($validated['organization_id'])) {
            $validated['organization_id'] = $site->organization_id;
        }

        $this->authorizeOrganizationForSite((int) $validated['organization_id']);

        $site->update($validated);

        return redirect()
            ->route('sites.edit', $site)
            ->with('success', 'Site updated successfully.');
    }

    public function destroy(Site $site)
    {
        $this->authorizeSiteAccess($site);

        if ($site->incidents()->count() > 0) {
            return redirect()
                ->route('sites.index')
                ->with('success', 'Site has incidents and cannot be deleted. Rename or mark inactive instead.');
        }

        if (method_exists($site, 'nvrSystems') && $site->nvrSystems()->count() > 0) {
            return redirect()
                ->route('sites.index')
                ->with('success', 'Site has video sources and cannot be deleted. Rename or mark inactive instead.');
        }

        $site->delete();

        return redirect()
            ->route('sites.index')
            ->with('success', 'Site deleted successfully.');
    }

    private function validateSite(Request $request): array
    {
        return $request->validate([
            'organization_id' => ['nullable', 'integer', 'exists:organizations,id'],
            'name' => ['required', 'string', 'max:255'],
            'site_type' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'max:100'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:100'],
            'time_zone' => ['nullable', 'string', 'max:100'],
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
            return Organization::pluck('id')
                ->map(fn ($id) => (int) $id)
                ->toArray();
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

    private function availableOrganizationsForSiteAssignment()
    {
        return Organization::whereIn('id', $this->visibleOrganizationIds())
            ->orderByRaw("FIELD(organization_type, 'platform_owner', 'master_account', 'channel_partner', 'customer', 'site_account')")
            ->orderBy('name')
            ->get();
    }

    private function authorizeOrganizationForSite(int $organizationId): void
    {
        if (!in_array($organizationId, $this->visibleOrganizationIds(), true)) {
            abort(403, 'You do not have access to create or edit sites for this organization.');
        }
    }

    private function authorizeSiteAccess(Site $site): void
    {
        if (!in_array((int) $site->organization_id, $this->visibleOrganizationIds(), true)) {
            abort(403, 'You do not have access to this site.');
        }
    }
}
