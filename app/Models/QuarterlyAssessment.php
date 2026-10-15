<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A single completed quarterly (90-day) assessment record for an employee.
 * The employee's current cycle is derived in the User model from the latest
 * assessment (or their start date if none yet).
 */
class QuarterlyAssessment extends Model
{
    protected $fillable = [
        'user_id',
        'assessed_on',
        'assessed_by',
        'notes',
    ];

    protected $casts = [
        'assessed_on' => 'date',
    ];

    /** The employee this assessment was performed on. */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** The admin/manager who recorded the assessment. */
    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }
}
