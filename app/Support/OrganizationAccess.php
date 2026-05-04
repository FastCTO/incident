<?php

namespace App\Support;

use App\Models\Organization;
use App\Models\Site;
use Illuminate\Support\Facades\Auth;

class OrganizationAccess
{
    public static function currentOrganization(): ?Organization
    {
        $organizationId = Auth::user()?->organization_id;

        if (!$organizationId) {
            return null;
        }

        return Organization::find($organizationId);
    }

    public static function currentUserIsPlatformOwner(): bool
    {
        return self::currentOrganization()?->organization_type === 'platform_owner';
    }

    public static function currentUserIsMasterAccount(): bool
    {
        $type = self::currentOrganization()?->organization_type;

        return in_array($type, ['master_account', 'channel_partner'], true);
    }

    public static function visibleOrganizationIds(): array
    {
        $organization = self::currentOrganization();

        if (!$organization) {
            return [];
        }

        if (self::currentUserIsPlatformOwner()) {
            return Organization::pluck('id')
                ->map(fn ($id) => (int) $id)
                ->toArray();
        }

        if (self::currentUserIsMasterAccount()) {
            return Organization::where('id', $organization->id)
                ->orWhere('parent_organization_id', $organization->id)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->toArray();
        }

        return [(int) $organization->id];
    }

    public static function canAccessOrganization(?int $organizationId): bool
    {
        if (!$organizationId) {
            return false;
        }

        return in_array((int) $organizationId, self::visibleOrganizationIds(), true);
    }

    public static function authorizeOrganization(?int $organizationId, string $message = 'You do not have access to this organization.'): void
    {
        if (!self::canAccessOrganization($organizationId)) {
            abort(403, $message);
        }
    }

    public static function activeSitesForCurrentUser()
    {
        return Site::with('organization')
            ->whereIn('organization_id', self::visibleOrganizationIds())
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
    }

    public static function defaultSiteIdForCurrentUser(): ?int
    {
        return Site::whereIn('organization_id', self::visibleOrganizationIds())
            ->where('status', 'active')
            ->orderBy('id')
            ->value('id');
    }

    public static function organizationIdForSite(?int $siteId): ?int
    {
        if (!$siteId) {
            return null;
        }

        return Site::where('id', $siteId)
            ->whereIn('organization_id', self::visibleOrganizationIds())
            ->value('organization_id');
    }

    public static function canAccessSite(?int $siteId): bool
    {
        if (!$siteId) {
            return true;
        }

        return Site::where('id', $siteId)
            ->whereIn('organization_id', self::visibleOrganizationIds())
            ->exists();
    }
}
