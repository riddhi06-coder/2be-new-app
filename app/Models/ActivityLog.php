<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'user_name', 'event', 'module', 'description',
        'subject_type', 'subject_id', 'properties', 'ip_address', 'url', 'method',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    /** Model class => friendly module label (used to group/label activity). */
    public const MODULE_MAP = [
        Document::class             => 'Documents',
        DocumentCategory::class     => 'Document Folders',
        Announcement::class         => 'Announcements',
        IncidentReport::class       => 'Incident Reports',
        CalendarEvent::class        => 'Community Calendar',
        User::class                 => 'Users & Employees',
        Role::class                 => 'Roles',
        Permission::class           => 'Permissions',
        EmailSettingsDetails::class => 'Email Settings',
        WasteDisposalDetails::class => 'Disposal Details',
        CesspoolSystemDetails::class=> 'Cesspool Records',
        SepticSystemDetails::class  => 'Septic Records',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Friendly module label for a model instance. */
    public static function moduleFor(Model $model): string
    {
        return self::MODULE_MAP[get_class($model)] ?? class_basename($model);
    }

    /** Best human-readable title for a model (name / title / reference / email / #id). */
    public static function titleFor(Model $model): string
    {
        foreach (['reference_no', 'title', 'name', 'email'] as $attr) {
            if (! empty($model->{$attr})) {
                return (string) $model->{$attr};
            }
        }
        return '#'.$model->getKey();
    }

    /**
     * Write an activity record. Safe to call anywhere; captures the current
     * user + request context automatically.
     */
    public static function write(string $event, string $module, string $description, ?Model $subject = null, ?array $properties = null): void
    {
        $user = Auth::user();
        $req  = request();

        static::create([
            'user_id'      => $user?->id,
            'user_name'    => $user?->name,
            'event'        => $event,
            'module'       => $module,
            'description'  => Str::limit($description, 500),
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id'   => $subject?->getKey(),
            'properties'   => $properties,
            'ip_address'   => $req?->ip(),
            'url'          => $req ? mb_substr($req->path(), 0, 255) : null,
            'method'       => $req?->method(),
        ]);
    }

    /** Bootstrap badge class for an event type. */
    public function getEventBadgeAttribute(): string
    {
        return [
            'created'  => 'bg-success',
            'updated'  => 'bg-primary',
            'deleted'  => 'bg-danger',
            'restored' => 'bg-info',
            'login'    => 'bg-secondary',
            'logout'   => 'bg-dark',
        ][$this->event] ?? 'bg-secondary';
    }
}
