<?php

namespace App\Models;

use App\Models\Concerns\TracksDeletedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentCategory extends Model
{
    use SoftDeletes, TracksDeletedBy;

    protected $fillable = ['name', 'slug', 'description', 'is_active', 'deleted_by'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
