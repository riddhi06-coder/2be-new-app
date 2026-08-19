<?php

namespace App\Models;

use App\Models\Concerns\TracksDeletedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use SoftDeletes, TracksDeletedBy;

    protected $fillable = [
        'title',
        'slug',
        'body',
        'image_path',
        'is_active',
        'published_at',
        'created_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'published_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /** Active announcements, newest first — used by the employee dashboard. */
    public function scopePublishedLatest($query)
    {
        return $query->where('is_active', true)
            ->orderByDesc('published_at')
            ->orderByDesc('id');
    }
}
