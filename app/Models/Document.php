<?php

namespace App\Models;

use App\Models\Concerns\TracksDeletedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes, TracksDeletedBy;

    protected $fillable = [
        'document_category_id',
        'title',
        'file_path',
        'original_name',
        'file_size',
        'mime_type',
        'is_public',
        'requires_acknowledgment',
        'acknowledgment_due',
        'user_id',
        'uploaded_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'requires_acknowledgment' => 'boolean',
        'acknowledgment_due' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    /** The employee who owns this document (legacy single-owner column). */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Employees a personal document is assigned to (one doc → many employees). */
    public function assignees(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /** Signature/acknowledgment records for this document (one per employee). */
    public function acknowledgments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DocumentAcknowledgment::class);
    }

    /** The acknowledgment record for a given employee, if they've signed. */
    public function acknowledgmentFor(?User $user): ?DocumentAcknowledgment
    {
        if (! $user) {
            return null;
        }

        return $this->acknowledgments->firstWhere('user_id', $user->id);
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /** Human-readable file size, e.g. "1.4 MB". */
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
