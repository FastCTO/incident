<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\IncidentEvent;
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
        ];

        $sort = $request->query('sort', 'datetime');
        $direction = $request->query('direction', 'desc');

        if (!array_key_exists($sort, $allowedSorts)) {
            $sort = 'datetime';
        }

        if (!in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        $sortColumn = $allowedSorts[$sort];

        $incidents = Incident::withCount('files')
            ->orderBy($sortColumn, $direction)
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('incidents.index', compact('incidents', 'sort', 'direction'));
    }

    public function create()
    {
        return view('incidents.create');
    }

    public function start()
    {
        $incident = Incident::create([
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

        $incident = Incident::create($validated);

        $this->logIncidentEvent(
            $incident,
            'incident_created',
            'Incident #' . $incident->id . ' was created.',
            [
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
        $incident->load('files.uploader');

        return view('incidents.show', compact('incident'));
    }

    public function edit(Incident $incident)
    {
        $incident->load('files.uploader');

        return view('incidents.edit', compact('incident'));
    }

    public function update(Request $request, Incident $incident)
    {
        $validated = $this->validateIncident($request);

        $before = $incident->only([
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
                'changed_fields' => $changedFields,
            ]
        );

        return redirect()
            ->route('incidents.edit', $incident)
            ->with('success', 'Incident updated successfully.');
    }

    public function destroy(Incident $incident)
    {
        $this->logIncidentEvent(
            $incident,
            'incident_deleted',
            'Incident #' . $incident->id . ' was deleted.',
            [
                'title' => $incident->title,
                'status' => $incident->status,
            ]
        );

        $incident->delete();

        return redirect()
            ->route('incidents.index')
            ->with('success', 'Incident deleted successfully.');
    }

    private function validateIncident(Request $request): array
    {
        return $request->validate([
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
