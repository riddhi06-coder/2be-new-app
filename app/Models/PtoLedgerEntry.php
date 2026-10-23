<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One line in an employee's PTO history: an opening balance, an accrual credit,
 * a usage debit, or a manual adjustment. The current balance is the signed sum
 * of all entries, so every change is auditable.
 */
class PtoLedgerEntry extends Model
{
    public const TYPES = [
        'opening'    => 'Opening balance',
        'accrual'    => 'Accrual',
        'usage'      => 'PTO used',
        'adjustment' => 'Adjustment',
    ];

    protected $fillable = [
        'user_id',
        'type',
        'hours',
        'effective_date',
        'note',
        'created_by',
    ];

    protected $casts = [
        'hours'          => 'decimal:2',
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
