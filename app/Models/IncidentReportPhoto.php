<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncidentReportPhoto extends Model
{
    protected $fillable = [
        'incident_report_id',
        'file_path',
        'original_name',
        'file_size',
        'mime_type',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(IncidentReport::class, 'incident_report_id');
    }

    public function getReadableSizeAttribute(): string
    {
        $bytes = (int) $this->file_size;
        if ($bytes <= 0) {
            return '—';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $power = min((int) floor(log($bytes, 1024)), count($units) - 1);

        return round($bytes / (1024 ** $power), 1).' '.$units[$power];
    }
}
