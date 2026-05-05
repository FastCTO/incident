<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\NvrSystem;
use App\Models\Organization;
use App\Models\Site;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): View
    {
        $organizationIds = $this->visibleOrganizationIds();
        $organization = $this->currentOrganization();

        $summary = [
            'organizations' => Organization::whereIn('id', $organizationIds)
                ->whereIn('organization_type', ['platform_owner', 'channel_partner', 'master_account'])
                ->count(),

            'customer_accounts' => Organization::whereIn('id', $organizationIds)
                ->whereIn('organization_type', ['customer', 'site_account'])
                ->count(),

            'sites' => Site::whereIn('organization_id', $organizationIds)
                ->count(),

            'video_sources' => NvrSystem::whereIn('organization_id', $organizationIds)
                ->count(),

            'need_audit' => NvrSystem::whereIn('organization_id', $organizationIds)
                ->where(function ($query) {
                    $query->whereNull('last_checked_at')
                        ->orWhere('last_checked_at', '<', now()->subDays(90));
                })
                ->count(),

            'active_incidents' => Incident::whereIn('organization_id', $organizationIds)
                ->whereNull('archived_at')
                ->count(),

            'sites_with_active_incidents' => Incident::whereIn('organization_id', $organizationIds)
                ->whereNull('archived_at')
                ->whereNotNull('site_id')
                ->distinct('site_id')
                ->count('site_id'),
        ];

        $recentIncidents = Incident::with(['organization', 'site'])
            ->whereIn('organization_id', $organizationIds)
            ->whereNull('archived_at')
            ->orderByDesc('incident_datetime')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $auditNeeded = NvrSystem::with(['organization', 'site'])
            ->whereIn('organization_id', $organizationIds)
            ->where(function ($query) {
                $query->whereNull('last_checked_at')
                    ->orWhere('last_checked_at', '<', now()->subDays(90));
            })
            ->orderBy('organization_id')
            ->orderBy('name')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'organization',
            'summary',
            'recentIncidents',
            'auditNeeded'
        ));
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
}
