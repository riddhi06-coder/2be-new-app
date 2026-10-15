<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * A single Google Business Profile review, synced into the app so the employee
 * portal can display what customers are saying about the company.
 */
class GoogleReview extends Model
{
    protected $fillable = [
        'external_id',
        'reviewer_name',
        'reviewer_photo_url',
        'rating',
        'comment',
        'reviewed_at',
        'reply_comment',
        'replied_at',
        'source',
        'is_visible',
    ];

    protected $casts = [
        'rating'      => 'integer',
        'reviewed_at' => 'datetime',
        'replied_at'  => 'datetime',
        'is_visible'  => 'boolean',
    ];

    /** Only reviews that should be shown in the portal. */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    /** Uppercase initials for the avatar fallback (e.g. "John D." -> "JD"). */
    public function getInitialsAttribute(): string
    {
        $parts = preg_split('/\s+/', trim($this->reviewer_name));
        $first = mb_substr($parts[0] ?? '', 0, 1);
        $last  = count($parts) > 1 ? mb_substr(end($parts), 0, 1) : '';

        return mb_strtoupper($first.$last) ?: '?';
    }
}
