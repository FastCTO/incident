<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    public function index()
    {
        $incidents = Incident::orderByDesc('incident_datetime')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('incidents.index', compact('incidents'));
    }

    public function create()
    {
        return view('incidents.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateIncident($request);

        $validated['created_by'] = Auth::id();

        Incident::create($validated);

        return redirect()
            ->route('incidents.index')
            ->with('success', 'Incident created successfully.');
    }

    public function show(Incident $incident)
    {
        return view('incidents.show', compact('incident'));
    }

    public function edit(Incident $incident)
    {
        return view('incidents.edit', compact('incident'));
    }

    public function update(Request $request, Incident $incident)
    {
        $validated = $this->validateIncident($request);

        $incident->update($validated);

        return redirect()
            ->route('incidents.show', $incident)
            ->with('success', 'Incident updated successfully.');
    }

    public function destroy(Incident $incident)
    {
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
}
