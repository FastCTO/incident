<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\VideoSourceAudit;
use App\Models\VideoSourceAuditAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoSourceAuditAttachmentController extends Controller
{
    public function store(Request $request, VideoSourceAudit $audit)
    {
        $this->authorizeAuditAccess($audit);

        $audit->load(['videoSource', 'organization', 'site']);

        $validated = $request->validate([
            'attachment_type' => ['required', 'string', 'max:100'],
            'file' => [
                'required',
                'file',
                'max:512000',
                'mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv,pdf,txt,log,csv,zip,json,xml',
            ],
            'notes' => ['nullable', 'string'],
        ]);

        $uploadedFile = $validated['file'];

        $originalFilename = $uploadedFile->getClientOriginalName();
        $extension = $uploadedFile->getClientOriginalExtension();

        $storedFilename = now()->format('Ymd_His') . '_' . Str::random(16);

        if ($extension) {
            $storedFilename .= '.' . strtolower($extension);
        }

        $directory = 'video-source-audit-attachments/' . $audit->id;

        $filePath = $uploadedFile->storeAs(
            $directory,
            $storedFilename,
            'public'
        );

        $absolutePath = Storage::disk('public')->path($filePath);

        $requestNow = request();

        VideoSourceAuditAttachment::create([
            'video_source_audit_id' => $audit->id,
            'nvr_system_id' => $audit->nvr_system_id,
            'organization_id' => $audit->organization_id,
            'site_id' => $audit->site_id,
            'uploaded_by' => Auth::id(),
            'attachment_type' => $validated['attachment_type'],
            'original_filename' => $originalFilename,
            'stored_filename' => $storedFilename,
            'file_path' => $filePath,
            'mime_type' => $uploadedFile->getClientMimeType(),
            'file_size' => $uploadedFile->getSize(),
            'sha256_hash' => hash_file('sha256', $absolutePath),
            'notes' => $request->input('notes'),
            'ip_address' => $requestNow->ip(),
            'user_agent' => $requestNow->userAgent(),
            'request_method' => $requestNow->method(),
            'request_path' => $requestNow->path(),
        ]);

        return redirect()
            ->route('video-source-audits.edit', $audit)
            ->with('success', 'Audit attachment uploaded and hashed successfully.');
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
