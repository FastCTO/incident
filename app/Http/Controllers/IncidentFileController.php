<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\IncidentEvent;
use App\Models\IncidentFile;
use App\Models\Organization;
use App\Models\Site;
use App\Support\OrganizationAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IncidentFileController extends Controller
{
    public function store(Request $request, Incident $incident)
    {
        $this->authorizeIncidentAccess($incident);

        if ($incident->archived_at) {
            return redirect()
                ->route('incidents.show', $incident)
                ->with('success', 'Archived incidents cannot be updated. Restore the incident first.');
        }

        /*
         * If the upload form includes current incident fields, save them first.
         * This prevents losing unsaved incident edits when the user clicks Upload File.
         */
        $incidentData = $request->validate([
            'incident_site_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) {
                    if (!$value) {
                        return;
                    }

                    $siteExists = Site::where('id', $value)
                        ->whereIn('organization_id', $this->visibleOrganizationIds())
                        ->exists();

                    if (!$siteExists) {
                        $fail('The selected site is not valid for your access level.');
                    }
                },
            ],
            'incident_title' => ['nullable', 'string', 'max:255'],
            'incident_type' => ['nullable', 'string', 'max:255'],
            'incident_status' => ['nullable', 'string', 'max:50'],
            'incident_location_name' => ['nullable', 'string', 'max:255'],
            'incident_address' => ['nullable', 'string', 'max:255'],
            'incident_datetime' => ['nullable', 'date'],
            'incident_summary' => ['nullable', 'string'],
            'incident_notes' => ['nullable', 'string'],
        ]);

        if (!empty($incidentData['incident_title'])) {
            $newSiteId = $incidentData['incident_site_id'] ?? null;
            $newOrganizationId = $this->organizationIdForSite($newSiteId) ?? $incident->organization_id;

            $incident->update([
                'organization_id' => $newOrganizationId,
                'site_id' => $newSiteId,
                'title' => $incidentData['incident_title'],
                'incident_type' => $incidentData['incident_type'] ?? null,
                'status' => $incidentData['incident_status'] ?? $incident->status,
                'location_name' => $incidentData['incident_location_name'] ?? null,
                'address' => $incidentData['incident_address'] ?? null,
                'incident_datetime' => $incidentData['incident_datetime'] ?? null,
                'summary' => $incidentData['incident_summary'] ?? null,
                'notes' => $incidentData['incident_notes'] ?? null,
            ]);

            $this->logIncidentEvent(
                $incident,
                'incident_updated',
                'Incident #' . $incident->id . ' was updated during file upload.',
                [
                    'organization_id' => $incident->organization_id,
                    'site_id' => $incident->site_id,
                    'source' => 'file_upload_form',
                ]
            );
        }

        $validated = $request->validate([
            'file' => [
                'required',
                'file',
                'max:102400',
                'mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv,pdf,txt,doc,docx,xls,xlsx,csv,zip',
            ],
            'notes' => ['nullable', 'string'],
        ]);

        $uploadedFile = $validated['file'];

        $originalFilename = $uploadedFile->getClientOriginalName();
        $extension = $uploadedFile->getClientOriginalExtension();
        $storedFilename = now()->format('Ymd_His') . '_' . Str::random(12);

        if ($extension) {
            $storedFilename .= '.' . strtolower($extension);
        }

        $directory = 'incident-files/' . $incident->id;

        $filePath = $uploadedFile->storeAs(
            $directory,
            $storedFilename,
            'public'
        );

        $absolutePath = Storage::disk('public')->path($filePath);

        $mimeType = $uploadedFile->getClientMimeType();
        $fileSize = $uploadedFile->getSize();
        $sha256Hash = hash_file('sha256', $absolutePath);

        $fileType = $this->detectFileType($mimeType, $extension);

        $incidentFile = IncidentFile::create([
            'incident_id' => $incident->id,
            'uploaded_by' => Auth::id(),
            'original_filename' => $originalFilename,
            'stored_filename' => $storedFilename,
            'file_path' => $filePath,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'file_type' => $fileType,
            'sha256_hash' => $sha256Hash,
            'notes' => $request->input('notes'),
        ]);

        $this->logIncidentEvent(
            $incident,
            'file_uploaded',
            'File uploaded: ' . $incidentFile->original_filename,
            [
                'organization_id' => $incident->organization_id,
                'site_id' => $incident->site_id,
                'incident_file_id' => $incidentFile->id,
                'original_filename' => $incidentFile->original_filename,
                'stored_filename' => $incidentFile->stored_filename,
                'file_path' => $incidentFile->file_path,
                'mime_type' => $incidentFile->mime_type,
                'file_size' => $incidentFile->file_size,
                'file_type' => $incidentFile->file_type,
                'sha256_hash' => $incidentFile->sha256_hash,
            ]
        );

        return redirect()
            ->route('incidents.edit', $incident)
            ->with('success', 'Incident updated, file uploaded, and file hash created successfully.');
    }

    public function destroy(Incident $incident, IncidentFile $file)
    {
        $this->authorizeIncidentAccess($incident);

        if ($incident->archived_at) {
            return redirect()
                ->route('incidents.show', $incident)
                ->with('success', 'Archived incident files cannot be removed. Restore the incident first.');
        }

        if ((int) $file->incident_id !== (int) $incident->id) {
            abort(404);
        }

        $fileMetadata = [
            'organization_id' => $incident->organization_id,
            'site_id' => $incident->site_id,
            'incident_file_id' => $file->id,
            'original_filename' => $file->original_filename,
            'stored_filename' => $file->stored_filename,
            'file_path' => $file->file_path,
            'mime_type' => $file->mime_type,
            'file_size' => $file->file_size,
            'file_type' => $file->file_type,
            'sha256_hash' => $file->sha256_hash,
        ];

        Storage::disk('public')->delete($file->file_path);

        $file->delete();

        $this->logIncidentEvent(
            $incident,
            'file_removed',
            'File removed: ' . ($fileMetadata['original_filename'] ?? 'Unknown file'),
            $fileMetadata
        );

        return redirect()
            ->route('incidents.edit', $incident)
            ->with('success', 'File removed from incident.');
    }

    private function detectFileType(?string $mimeType, ?string $extension): string
    {
        $mimeType = strtolower($mimeType ?? '');
        $extension = strtolower($extension ?? '');

        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }

        if (str_starts_with($mimeType, 'video/')) {
            return 'video';
        }

        if ($extension === 'pdf') {
            return 'pdf';
        }

        if (in_array($extension, ['doc', 'docx', 'txt', 'csv', 'xls', 'xlsx'], true)) {
            return 'document';
        }

        if ($extension === 'zip') {
            return 'archive';
        }

        return 'other';
    }

    private function authorizeIncidentAccess(Incident $incident): void
    {
        if (!in_array((int) $incident->organization_id, $this->visibleOrganizationIds(), true)) {
            abort(403, 'You do not have access to this incident.');
        }
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

    private function currentUserIsMasterAccount(): bool
    {
        $type = $this->currentOrganization()?->organization_type;

        return in_array($type, ['master_account', 'channel_partner'], true);
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

        if ($this->currentUserIsMasterAccount()) {
            return Organization::where('id', $organization->id)
                ->orWhere('parent_organization_id', $organization->id)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->toArray();
        }

        return [(int) $organization->id];
    }

    private function organizationIdForSite(?int $siteId): ?int
    {
        if (!$siteId) {
            return null;
        }

        return Site::where('id', $siteId)
            ->whereIn('organization_id', $this->visibleOrganizationIds())
            ->value('organization_id');
    }

    private function logIncidentEvent(Incident $incident, string $eventType, string $description, array $metadata = []): void
    {
        $request = request();

        IncidentEvent::create([
            'incident_id' => $incident->id,
            'user_id' => Auth::id(),
            'event_type' => $eventType,
            'description' => $description,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'request_method' => $request->method(),
            'request_path' => $request->path(),
            'metadata' => $metadata,
        ]);
    }
}
