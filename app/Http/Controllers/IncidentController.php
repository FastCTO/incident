<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\IncidentEvent;
use App\Models\Organization;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        $allowedSorts = [
            'id' => 'id',
            'title' => 'title',
            'type' => 'incident_type',
            'status' => 'status',
            'location' => 'location_name',
            'datetime' => 'incident_datetime',
            'created' => 'created_at',
            'archived' => 'archived_at',
        ];

        $sort = $request->query('sort', 'datetime');
        $direction = $request->query('direction', 'desc');
        $showArchived = $request->boolean('archived');

        if (!array_key_exists($sort, $allowedSorts)) {
            $sort = 'datetime';
        }

        if (!in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        $sortColumn = $allowedSorts[$sort];

        $incidentQuery = Incident::with(['organization', 'site'])
            ->withCount('files')
            ->whereIn('organization_id', $this->visibleOrganizationIds());

        if ($showArchived) {
            $incidentQuery->whereNotNull('archived_at');
        } else {
            $incidentQuery->whereNull('archived_at');
        }

        $incidents = $incidentQuery
            ->orderBy($sortColumn, $direction)
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('incidents.index', compact('incidents', 'sort', 'direction', 'showArchived'));
    }

    public function create()
    {
        $sites = $this->sitesForCurrentUser();

        return view('incidents.create', compact('sites'));
    }

    public function start()
    {
        $user = Auth::user();
        $siteId = $this->defaultSiteIdForCurrentUser();
        $organizationId = $this->organizationIdForSite($siteId) ?? $user?->organization_id;

        $incident = Incident::create([
            'organization_id' => $organizationId,
            'site_id' => $siteId,
            'title' => 'Starting new incident...',
            'incident_type' => null,
            'status' => 'open',
            'location_name' => null,
            'address' => null,
            'incident_datetime' => now(),
            'summary' => null,
            'notes' => null,
            'created_by' => Auth::id(),
        ]);

        $incident->update([
            'title' => 'Incident #' . $incident->id . ' - ',
        ]);

        $this->logIncidentEvent(
            $incident,
            'incident_created',
            'Incident #' . $incident->id . ' was started.',
            [
                'organization_id' => $incident->organization_id,
                'site_id' => $incident->site_id,
                'title' => $incident->title,
                'status' => $incident->status,
                'created_by' => Auth::id(),
            ]
        );

        return redirect()
            ->route('incidents.edit', $incident)
            ->with('success', 'New incident started. Add details and upload files below.');
    }

    public function store(Request $request)
    {
        $validated = $this->validateIncident($request);

        $validated['created_by'] = Auth::id();

        if (empty($validated['site_id'])) {
            $validated['site_id'] = $this->defaultSiteIdForCurrentUser();
        }

        $validated['organization_id'] = $this->organizationIdForSite($validated['site_id'] ?? null)
            ?? Auth::user()?->organization_id;

        $incident = Incident::create($validated);

        $this->logIncidentEvent(
            $incident,
            'incident_created',
            'Incident #' . $incident->id . ' was created.',
            [
                'organization_id' => $incident->organization_id,
                'site_id' => $incident->site_id,
                'title' => $incident->title,
                'status' => $incident->status,
                'created_by' => Auth::id(),
            ]
        );

        return redirect()
            ->route('incidents.edit', $incident)
            ->with('success', 'Incident created successfully. You can now attach files.');
    }

    public function show(Incident $incident)
    {
        $this->authorizeIncidentAccess($incident);

        $incident->load([
            'organization',
            'site',
            'files.uploader',
            'events.actor',
            'archivedBy',
        ]);

        return view('incidents.show', compact('incident'));
    }

    public function edit(Incident $incident)
    {
        $this->authorizeIncidentAccess($incident);

        if ($incident->archived_at) {
            return redirect()
                ->route('incidents.show', $incident)
                ->with('success', 'Archived incidents cannot be edited. Restore the incident first.');
        }

        $incident->load([
            'organization',
            'site',
            'files.uploader',
        ]);

        $sites = $this->sitesForCurrentUser();

        return view('incidents.edit', compact('incident', 'sites'));
    }

    public function update(Request $request, Incident $incident)
    {
        $this->authorizeIncidentAccess($incident);

        if ($incident->archived_at) {
            return redirect()
                ->route('incidents.show', $incident)
                ->with('success', 'Archived incidents cannot be updated. Restore the incident first.');
        }

        $validated = $this->validateIncident($request);

        if (!empty($validated['site_id'])) {
            $validated['organization_id'] = $this->organizationIdForSite($validated['site_id'])
                ?? $incident->organization_id;
        }

        $before = $incident->only([
            'organization_id',
            'site_id',
            'title',
            'incident_type',
            'status',
            'location_name',
            'address',
            'incident_datetime',
            'summary',
            'notes',
        ]);

        $incident->update($validated);

        $afterIncident = $incident->fresh();

        $after = $afterIncident->only([
            'organization_id',
            'site_id',
            'title',
            'incident_type',
            'status',
            'location_name',
            'address',
            'incident_datetime',
            'summary',
            'notes',
        ]);

        $changedFields = [];

        foreach ($after as $field => $newValue) {
            $oldValue = $before[$field] ?? null;

            if ((string) $oldValue !== (string) $newValue) {
                $changedFields[$field] = [
                    'before' => $oldValue,
                    'after' => $newValue,
                ];
            }
        }

        $this->logIncidentEvent(
            $incident,
            'incident_updated',
            'Incident #' . $incident->id . ' was updated.',
            [
                'organization_id' => $incident->organization_id,
                'site_id' => $incident->site_id,
                'changed_fields' => $changedFields,
            ]
        );

        return redirect()
            ->route('incidents.edit', $incident)
            ->with('success', 'Incident updated successfully.');
    }

    public function destroy(Incident $incident)
    {
        $this->authorizeIncidentAccess($incident);

        if ($incident->archived_at) {
            return redirect()
                ->route('incidents.show', $incident)
                ->with('success', 'Incident is already archived.');
        }

        $incident->update([
            'archived_at' => now(),
            'archived_by' => Auth::id(),
        ]);

        $this->logIncidentEvent(
            $incident,
            'incident_archived',
            'Incident #' . $incident->id . ' was archived.',
            [
                'organization_id' => $incident->organization_id,
                'site_id' => $incident->site_id,
                'title' => $incident->title,
                'status' => $incident->status,
                'archived_by' => Auth::id(),
                'archived_at' => $incident->archived_at?->toDateTimeString(),
            ]
        );

        return redirect()
            ->route('incidents.index')
            ->with('success', 'Incident archived successfully.');
    }

    public function restore(Incident $incident)
    {
        $this->authorizeIncidentAccess($incident);

        if (!$incident->archived_at) {
            return redirect()
                ->route('incidents.show', $incident)
                ->with('success', 'Incident is already active.');
        }

        $previousArchivedAt = $incident->archived_at;
        $previousArchivedBy = $incident->archived_by;

        $incident->update([
            'archived_at' => null,
            'archived_by' => null,
        ]);

        $this->logIncidentEvent(
            $incident,
            'incident_restored',
            'Incident #' . $incident->id . ' was restored from archive.',
            [
                'organization_id' => $incident->organization_id,
                'site_id' => $incident->site_id,
                'previous_archived_at' => $previousArchivedAt?->toDateTimeString(),
                'previous_archived_by' => $previousArchivedBy,
                'restored_by' => Auth::id(),
            ]
        );

        return redirect()
            ->route('incidents.show', $incident)
            ->with('success', 'Incident restored successfully.');
    }

    private function validateIncident(Request $request): array
    {
        return $request->validate([
            'site_id' => [
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
            'title' => ['required', 'string', 'max:255'],
            'incident_type' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:50'],
            'location_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'incident_datetime' => ['nullable', 'date'],
            'summary' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);
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

    private function sitesForCurrentUser()
    {
        return Site::with('organization')
            ->whereIn('organization_id', $this->visibleOrganizationIds())
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
    }

    private function defaultSiteIdForCurrentUser(): ?int
    {
        return Site::whereIn('organization_id', $this->visibleOrganizationIds())
            ->where('status', 'active')
            ->orderBy('id')
            ->value('id');
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
