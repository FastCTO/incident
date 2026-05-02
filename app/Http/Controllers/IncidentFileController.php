<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\IncidentFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IncidentFileController extends Controller
{
    public function store(Request $request, Incident $incident)
    {
        /*
         * If the upload form includes current incident fields, save them first.
         * This prevents losing unsaved incident edits when the user clicks Upload File.
         */
        $incidentData = $request->validate([
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
            $incident->update([
                'title' => $incidentData['incident_title'],
                'incident_type' => $incidentData['incident_type'] ?? null,
                'status' => $incidentData['incident_status'] ?? $incident->status,
                'location_name' => $incidentData['incident_location_name'] ?? null,
                'address' => $incidentData['incident_address'] ?? null,
                'incident_datetime' => $incidentData['incident_datetime'] ?? null,
                'summary' => $incidentData['incident_summary'] ?? null,
                'notes' => $incidentData['incident_notes'] ?? null,
            ]);
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

        IncidentFile::create([
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

        return redirect()
            ->route('incidents.edit', $incident)
            ->with('success', 'Incident updated, file uploaded, and file hash created successfully.');
    }

    public function destroy(Incident $incident, IncidentFile $file)
    {
        if ($file->incident_id !== $incident->id) {
            abort(404);
        }

        Storage::disk('public')->delete($file->file_path);

        $file->delete();

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
}
