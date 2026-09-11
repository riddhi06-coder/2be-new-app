<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminNotification extends Model
{
    protected $fillable = [
        'user_id', 'actor_id', 'actor_name', 'type', 'title', 'message', 'url', 'icon', 'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    /**
     * Create a notification for every admin/manager (any active, non-employee
     * user). Called when an employee performs an important action.
     *
     * $data keys: type, title, message, url, icon, actor_id, actor_name
     */
    public static function notifyAdmins(array $data): void
    {
        $recipientIds = User::where('is_active', true)
            ->whereHas('role', fn ($q) => $q->where('slug', '!=', 'employee'))
            ->pluck('id');

        if ($recipientIds->isEmpty()) {
            return;
        }

        $now  = now();
        $rows = $recipientIds->map(fn ($uid) => [
            'user_id'    => $uid,
            'actor_id'   => $data['actor_id']   ?? null,
            'actor_name' => $data['actor_name'] ?? null,
            'type'       => $data['type']       ?? 'general',
            'title'      => $data['title'],
            'message'    => isset($data['message']) ? mb_substr($data['message'], 0, 500) : null,
            'url'        => $data['url']  ?? null,
            'icon'       => $data['icon'] ?? 'fa fa-bell',
            'read_at'    => null,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        static::insert($rows);
    }

    /** Notify one specific user (e.g. an employee). Same $data keys as notifyAdmins. */
    public static function notifyUser($userId, array $data): void
    {
        if (! $userId) {
            return;
        }

        static::create([
            'user_id'    => $userId,
            'actor_id'   => $data['actor_id']   ?? null,
            'actor_name' => $data['actor_name'] ?? null,
            'type'       => $data['type']       ?? 'general',
            'title'      => $data['title'],
            'message'    => isset($data['message']) ? mb_substr($data['message'], 0, 500) : null,
            'url'        => $data['url']  ?? null,
            'icon'       => $data['icon'] ?? 'fa fa-bell',
            'read_at'    => null,
        ]);
    }

    /** Relative "time ago" label. */
    public function getAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
}
