<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentAcknowledgment extends Model
{
    protected $fillable = [
        'document_id',
        'user_id',
        'signed_name',
        'ip_address',
        'signed_pdf_path',
        'acknowledged_at',
    ];

    protected $casts = [
        'acknowledged_at' => 'datetime',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    /** The employee who signed. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Public URL to the stamped signed PDF, if generated. */
    public function getSignedPdfUrlAttribute(): ?string
    {
        return $this->signed_pdf_path ? asset($this->signed_pdf_path) : null;
    }
}
