<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class IncidentFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_id',
        'uploaded_by',
        'original_filename',
        'stored_filename',
        'file_path',
        'mime_type',
        'file_size',
        'file_type',
        'sha256_hash',
        'notes',
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }

    public function getHumanFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return '-';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
