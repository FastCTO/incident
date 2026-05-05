<?php

namespace App\Http\Controllers;

use App\Models\NvrSystem;
use App\Models\Organization;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NvrSystemController extends Controller
{
    public function index(Request $request)
    {
        $organizationIds = $this->visibleOrganizationIds();

        $organizationId = $request->query('organization_id');
        $siteId = $request->query('site_id');
        $needsAudit = $request->boolean('needs_audit');

        $baseQuery = NvrSystem::query()
            ->whereIn('organization_id', $organizationIds);

        if ($organizationId && in_array((int) $organizationId, $organizationIds, true)) {
            $baseQuery->where('organization_id', (int) $organizationId);
        }

        if ($siteId) {
            $siteAllowed = Site::where('id', (int) $siteId)
                ->whereIn('organization_id', $organizationIds)
                ->exists();

            if ($siteAllowed) {
                $baseQuery->where('site_id', (int) $siteId);
            }
        }

        if ($needsAudit) {
            $baseQuery->where(function ($query) {
                $query->whereNull('last_checked_at')
                    ->orWhere('last_checked_at', '<', now()->subDays(90));
            });
        }

        $summaryBase = clone $baseQuery;

        $summary = [
            'video_sources' => (clone $summaryBase)->count(),

            'sites_with_video' => (clone $summaryBase)
                ->whereNotNull('site_id')
                ->distinct('site_id')
                ->count('site_id'),

            'cameras' => (int) (clone $summaryBase)
                ->sum('camera_count'),

            'need_audit' => (clone $summaryBase)
                ->where(function ($query) {
                    $query->whereNull('last_checked_at')
                        ->orWhere('last_checked_at', '<', now()->subDays(90));
                })
                ->count(),

            'recently_checked' => (clone $summaryBase)
                ->whereNotNull('last_checked_at')
                ->where('last_checked_at', '>=', now()->subDays(90))
                ->count(),

            'audited' => (clone $summaryBase)
                ->has('audits')
                ->count(),
        ];

        $nvrSystems = $baseQuery
            ->with(['site', 'organization', 'latestAudit'])
            ->withCount('audits')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('nvr-systems.index', compact(
            'nvrSystems',
            'summary',
            'organizationId',
            'siteId',
            'needsAudit'
        ));
    }

    public function create()
    {
        $sites = $this->sitesForVisibleOrganizations();

        return view('nvr-systems.create', compact('sites'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateNvrSystem($request);

        $site = Site::where('id', $validated['site_id'])
            ->whereIn('organization_id', $this->visibleOrganizationIds())
            ->firstOrFail();

        $validated['organization_id'] = $site->organization_id;

        $nvrSystem = NvrSystem::create($validated);

        return redirect()
            ->route('nvr-systems.edit', $nvrSystem)
            ->with('success', 'Video source profile created successfully.');
    }

    public function edit(NvrSystem $nvrSystem)
    {
        $this->authorizeNvrAccess($nvrSystem);

        $sites = $this->sitesForVisibleOrganizations();

        return view('nvr-systems.edit', compact('nvrSystem', 'sites'));
    }

    public function update(Request $request, NvrSystem $nvrSystem)
    {
        $this->authorizeNvrAccess($nvrSystem);

        $validated = $this->validateNvrSystem($request);

        $site = Site::where('id', $validated['site_id'])
            ->whereIn('organization_id', $this->visibleOrganizationIds())
            ->firstOrFail();

        $validated['organization_id'] = $site->organization_id;

        $nvrSystem->update($validated);

        return redirect()
            ->route('nvr-systems.edit', $nvrSystem)
            ->with('success', 'Video source profile updated successfully.');
    }

    public function destroy(NvrSystem $nvrSystem)
    {
        $this->authorizeNvrAccess($nvrSystem);

        $nvrSystem->delete();

        return redirect()
            ->route('nvr-systems.index')
            ->with('success', 'Video source profile deleted successfully.');
    }

    private function validateNvrSystem(Request $request): array
    {
        return $request->validate([
            'site_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $siteExists = Site::where('id', $value)
                        ->whereIn('organization_id', $this->visibleOrganizationIds())
                        ->exists();

                    if (!$siteExists) {
                        $fail('The selected site is not valid for your access level.');
                    }
                },
            ],
            'name' => ['required', 'string', 'max:255'],
            'system_type' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'max:100'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'hostname' => ['nullable', 'string', 'max:255'],
            'ip_address' => ['nullable', 'string', 'max:100'],
            'local_url' => ['nullable', 'string', 'max:255'],
            'remote_url' => ['nullable', 'string', 'max:255'],
            'camera_count' => ['nullable', 'integer', 'min:0'],
            'estimated_retention_days' => ['nullable', 'integer', 'min:0'],
            'storage_notes' => ['nullable', 'string'],
            'access_notes' => ['nullable', 'string'],
            'last_checked_at' => ['nullable', 'date'],
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

    private function sitesForVisibleOrganizations()
    {
        return Site::with('organization')
            ->whereIn('organization_id', $this->visibleOrganizationIds())
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
    }

    private function authorizeNvrAccess(NvrSystem $nvrSystem): void
    {
        if (!in_array((int) $nvrSystem->organization_id, $this->visibleOrganizationIds(), true)) {
            abort(403, 'You do not have access to this video source profile.');
        }
    }
}
