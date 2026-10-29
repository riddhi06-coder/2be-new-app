<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One line in an employee's PTO history (tracked in DAYS):
 *   - allotment : the PTO given for the year (+)
 *   - carryover : days carried from the previous year, max 5 (+)
 *   - usage     : a day (or part-day) taken off, with its date (-)
 *   - adjustment: a manual correction (+/-)
 *
 * The remaining balance for a year is the signed sum of that year's entries,
 * so every change is auditable. Payroll remains the official record — this is
 * a simple, admin-maintained view so the crew can see what they have left.
 */
class PtoLedgerEntry extends Model
{
    public const TYPES = [
        'allotment'  => 'Annual allotment',
        'carryover'  => 'Carried over',
        'usage'      => 'PTO used',
        'adjustment' => 'Adjustment',
    ];

    protected $fillable = [
        'user_id',
        'type',
        'days',
        'effective_date',
        'note',
        'created_by',
    ];

    protected $casts = [
        'days'           => 'decimal:2',
        'effective_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }
}
