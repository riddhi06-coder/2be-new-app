<?php

namespace App\Models;

use App\Models\Concerns\TracksDeletedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncidentReport extends Model
{
    use SoftDeletes, TracksDeletedBy;

    protected $fillable = [
        'reference_no',
        'reported_by',
        'employee_id',
        'reporter_name',
        'incident_date',
        'incident_time',
        'location',
        'category',
        'severity',
        'description',
        'immediate_action',
        'witnesses',
        'status',
        'review_notes',
        'reviewed_by',
        'reviewed_at',
        'created_by',
        'deleted_by',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'reviewed_at'   => 'datetime',
    ];

    public const CATEGORIES = [
        'injury'          => 'Injury',
        'near-miss'       => 'Near-miss',
        'property-damage' => 'Property Damage',
        'environmental'   => 'Environmental / Spill',
        'vehicle'         => 'Vehicle',
        'equipment'       => 'Equipment',
        'other'           => 'Other',
    ];

    public const SEVERITIES = [
        'minor'    => 'Minor',
        'moderate' => 'Moderate',
        'serious'  => 'Serious',
    ];

    public const STATUSES = [
        'open'         => 'Open',
        'under-review' => 'Under Review',
        'closed'       => 'Closed',
    ];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(IncidentReportPhoto::class);
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? ucfirst((string) $this->category);
    }

    public function getSeverityLabelAttribute(): string
    {
        return self::SEVERITIES[$this->severity] ?? ucfirst((string) $this->severity);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }

    /** Bootstrap badge class for the current status. */
    public function getStatusBadgeAttribute(): string
    {
        return [
            'open'         => 'bg-danger',
            'under-review' => 'bg-warning text-dark',
            'closed'       => 'bg-success',
        ][$this->status] ?? 'bg-secondary';
    }

    /** Bootstrap badge class for the current severity. */
    public function getSeverityBadgeAttribute(): string
    {
        return [
            'minor'    => 'bg-info',
            'moderate' => 'bg-warning text-dark',
            'serious'  => 'bg-danger',
        ][$this->severity] ?? 'bg-secondary';
    }
}
