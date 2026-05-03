<?php

namespace App\Http\Controllers;

use App\Models\NvrSystem;
use App\Models\Organization;
use App\Models\VideoSourceAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoSourceAuditController extends Controller
{
    public function create(NvrSystem $nvrSystem)
    {
        $this->authorizeVideoSourceAccess($nvrSystem);

        $nvrSystem->load(['organization', 'site']);

        return view('video-source-audits.create', compact('nvrSystem'));
    }

    public function store(Request $request, NvrSystem $nvrSystem)
    {
        $this->authorizeVideoSourceAccess($nvrSystem);

        $validated = $this->validateAudit($request);

        $httpRequest = request();

        $validated['nvr_system_id'] = $nvrSystem->id;
        $validated['organization_id'] = $nvrSystem->organization_id;
        $validated['site_id'] = $nvrSystem->site_id;
        $validated['performed_by'] = Auth::id();
        $validated['ip_address'] = $httpRequest->ip();
        $validated['user_agent'] = $httpRequest->userAgent();
        $validated['request_method'] = $httpRequest->method();
        $validated['request_path'] = $httpRequest->path();

        if (empty($validated['performed_at'])) {
            $validated['performed_at'] = now();
        }

        $audit = VideoSourceAudit::create($validated);

        $this->updateVideoSourceFromAudit($nvrSystem, $audit);

        return redirect()
            ->route('nvr-systems.edit', $nvrSystem)
            ->with('success', 'Video source audit saved successfully.');
    }

    public function edit(VideoSourceAudit $audit)
    {
        $this->authorizeAuditAccess($audit);

        $audit->load(['videoSource', 'organization', 'site', 'performer']);

        return view('video-source-audits.edit', compact('audit'));
    }

    public function update(Request $request, VideoSourceAudit $audit)
    {
        $this->authorizeAuditAccess($audit);

        $validated = $this->validateAudit($request);

        $audit->update($validated);

        if ($audit->videoSource) {
            $this->updateVideoSourceFromAudit($audit->videoSource, $audit);
        }

        return redirect()
            ->route('video-source-audits.edit', $audit)
            ->with('success', 'Video source audit updated successfully.');
    }

    public function destroy(VideoSourceAudit $audit)
    {
        $this->authorizeAuditAccess($audit);

        $nvrSystem = $audit->videoSource;

        $audit->delete();

        return redirect()
            ->route('nvr-systems.edit', $nvrSystem)
            ->with('success', 'Video source audit deleted successfully.');
    }

    private function validateAudit(Request $request): array
    {
        return $request->validate([
            'audit_type' => ['required', 'string', 'max:100'],
            'audit_status' => ['required', 'string', 'max:100'],
            'performed_at' => ['nullable', 'date'],

            'serial_number_observed' => ['nullable', 'string', 'max:255'],
            'manufacturer_observed' => ['nullable', 'string', 'max:255'],
            'model_observed' => ['nullable', 'string', 'max:255'],
            'firmware_version' => ['nullable', 'string', 'max:255'],
            'software_version' => ['nullable', 'string', 'max:255'],
            'os_version' => ['nullable', 'string', 'max:255'],

            'hostname_observed' => ['nullable', 'string', 'max:255'],
            'source_ip_observed' => ['nullable', 'string', 'max:255'],
            'mac_address_observed' => ['nullable', 'string', 'max:255'],

            'system_datetime' => ['nullable', 'date'],
            'system_time_zone' => ['nullable', 'string', 'max:255'],
            'time_drift_notes' => ['nullable', 'string'],

            'total_storage' => ['nullable', 'string', 'max:255'],
            'used_storage' => ['nullable', 'string', 'max:255'],
            'available_storage' => ['nullable', 'string', 'max:255'],
            'storage_health' => ['nullable', 'string', 'max:255'],

            'oldest_recording_at' => ['nullable', 'date'],
            'estimated_retention_days' => ['nullable', 'integer', 'min:0'],
            'recording_mode' => ['nullable', 'string', 'max:255'],

            'total_camera_count' => ['nullable', 'integer', 'min:0'],
            'active_camera_count' => ['nullable', 'integer', 'min:0'],
            'offline_camera_count' => ['nullable', 'integer', 'min:0'],
            'disabled_camera_count' => ['nullable', 'integer', 'min:0'],

            'camera_view_notes' => ['nullable', 'string'],

            'admin_user_count' => ['nullable', 'integer', 'min:0'],
            'standard_user_count' => ['nullable', 'integer', 'min:0'],
            'unknown_user_count' => ['nullable', 'integer', 'min:0'],

            'last_login_notes' => ['nullable', 'string'],
            'failed_login_notes' => ['nullable', 'string'],
            'unusual_activity_notes' => ['nullable', 'string'],
            'security_notes' => ['nullable', 'string'],
            'retention_notes' => ['nullable', 'string'],
            'overall_notes' => ['nullable', 'string'],
            'recommended_actions' => ['nullable', 'string'],

            'next_audit_due_at' => ['nullable', 'date'],
        ]);
    }

    private function updateVideoSourceFromAudit(NvrSystem $nvrSystem, VideoSourceAudit $audit): void
    {
        $updates = [
            'last_checked_at' => $audit->performed_at ?? now(),
        ];

        if ($audit->serial_number_observed) {
            $updates['serial_number'] = $audit->serial_number_observed;
        }

        if ($audit->manufacturer_observed) {
            $updates['manufacturer'] = $audit->manufacturer_observed;
        }

        if ($audit->model_observed) {
            $updates['model'] = $audit->model_observed;
        }

        if ($audit->hostname_observed) {
            $updates['hostname'] = $audit->hostname_observed;
        }

        if ($audit->source_ip_observed) {
            $updates['ip_address'] = $audit->source_ip_observed;
        }

        if (!is_null($audit->total_camera_count)) {
            $updates['camera_count'] = $audit->total_camera_count;
        }

        if (!is_null($audit->estimated_retention_days)) {
            $updates['estimated_retention_days'] = $audit->estimated_retention_days;
        }

        $nvrSystem->update($updates);
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

    private function authorizeVideoSourceAccess(NvrSystem $nvrSystem): void
    {
        if (!in_array((int) $nvrSystem->organization_id, $this->visibleOrganizationIds(), true)) {
            abort(403, 'You do not have access to this video source.');
        }
    }

    private function authorizeAuditAccess(VideoSourceAudit $audit): void
    {
        if (!in_array((int) $audit->organization_id, $this->visibleOrganizationIds(), true)) {
            abort(403, 'You do not have access to this video source audit.');
        }
    }
}
