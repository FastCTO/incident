<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\VideoSourceAudit;
use App\Models\VideoSourceAuditAddendum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoSourceAuditAddendumController extends Controller
{
    public function store(Request $request, VideoSourceAudit $audit)
    {
        $this->authorizeAuditAccess($audit);

        $validated = $request->validate([
            'addendum_type' => ['required', 'string', 'max:100'],
            'body' => ['required', 'string'],
        ]);

        $httpRequest = request();

        VideoSourceAuditAddendum::create([
            'video_source_audit_id' => $audit->id,
            'nvr_system_id' => $audit->nvr_system_id,
            'organization_id' => $audit->organization_id,
            'site_id' => $audit->site_id,
            'added_by' => Auth::id(),
            'addendum_type' => $validated['addendum_type'],
            'body' => $validated['body'],
            'ip_address' => $httpRequest->ip(),
            'user_agent' => $httpRequest->userAgent(),
            'request_method' => $httpRequest->method(),
            'request_path' => $httpRequest->path(),
        ]);

        return redirect()
            ->route('video-source-audits.edit', $audit)
            ->with('success', 'Audit addendum added successfully.');
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
        return $this->currentOrganization()?->organization_type === 'channel_partner';
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

    private function authorizeAuditAccess(VideoSourceAudit $audit): void
    {
        if (!in_array((int) $audit->organization_id, $this->visibleOrganizationIds(), true)) {
            abort(403, 'You do not have access to this video source audit.');
        }
    }
}
