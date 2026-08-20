<?php

namespace App\Models;

use App\Models\Concerns\TracksDeletedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalendarEvent extends Model
{
    use SoftDeletes, TracksDeletedBy;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'all_day',
        'start_time',
        'end_time',
        'category',
        'location',
        'is_active',
        'created_by',
        'deleted_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'all_day'    => 'boolean',
        'is_active'  => 'boolean',
    ];

    /** Category => [label, color] used for the calendar legend + event colors. */
    public const CATEGORIES = [
        'holiday'  => ['label' => 'Holiday',  'color' => '#dc3545'],
        'meeting'  => ['label' => 'Meeting',  'color' => '#0d6efd'],
        'training' => ['label' => 'Training', 'color' => '#6f42c1'],
        'pto'      => ['label' => 'PTO',      'color' => '#fd7e14'],
        'other'    => ['label' => 'Other',    'color' => '#198754'],
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category]['label'] ?? ucfirst((string) $this->category);
    }

    public function getColorAttribute(): string
    {
        return self::CATEGORIES[$this->category]['color'] ?? '#6c757d';
    }
}
