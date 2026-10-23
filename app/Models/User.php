<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Concerns\TracksDeletedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, TracksDeletedBy;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar',
        'password',
        'role_id',
        'is_active',
        'welcome_email_sent_at',
        'start_date',
        'accrues_pto',
        'last_assessment_at',
        'assessment_due_notified_at',
        'deleted_by',
    ];

    /** Number of days between quarterly assessments (90-day cycle). */
    public const ASSESSMENT_INTERVAL_DAYS = 90;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'          => 'datetime',
            'password'                   => 'hashed',
            'is_active'                  => 'boolean',
            'accrues_pto'                => 'boolean',
            'welcome_email_sent_at'      => 'datetime',
            'start_date'                 => 'date',
            'last_assessment_at'         => 'date',
            'assessment_due_notified_at' => 'datetime',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /** Public URL of the uploaded avatar, or null if none set. */
    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar ? asset($this->avatar) : null;
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(self::class, 'deleted_by');
    }

    /** Personal documents assigned to this employee. */
    public function assignedDocuments(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Document::class);
    }

    /** Document acknowledgments (signatures) made by this employee. */
    public function documentAcknowledgments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DocumentAcknowledgment::class);
    }

    /** Admin notifications addressed to this user (as a recipient). */
    public function adminNotifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AdminNotification::class);
    }

    /** Completed quarterly assessments for this employee (newest first). */
    public function quarterlyAssessments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(QuarterlyAssessment::class)->orderByDesc('assessed_on');
    }

    /**
     * The date the current 90-day cycle is measured from: the last completed
     * assessment, or the start date if none has been completed yet.
     */
    public function assessmentBaseline(): ?\Illuminate\Support\Carbon
    {
        return $this->last_assessment_at ?? $this->start_date;
    }

    /** Date the next quarterly assessment becomes due (null if no start date). */
    public function nextAssessmentDueDate(): ?\Illuminate\Support\Carbon
    {
        $base = $this->assessmentBaseline();

        return $base ? $base->copy()->addDays(self::ASSESSMENT_INTERVAL_DAYS) : null;
    }

    /** True when the employee has a start date and the next assessment is due/overdue. */
    public function isAssessmentDue(): bool
    {
        $due = $this->nextAssessmentDueDate();

        return $due !== null && $due->startOfDay()->lte(now()->startOfDay());
    }

    /* ===================== PTO (Paid Time Off) ===================== */

    /** PTO ledger entries (opening / accrual / usage / adjustment), newest first. */
    public function ptoLedgerEntries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PtoLedgerEntry::class)->orderByDesc('effective_date')->orderByDesc('id');
    }

    /** Completed years of service since the hire (start) date; null if unset. */
    public function ptoYearsOfService(): ?int
    {
        return $this->start_date ? $this->start_date->diffInYears(now()) : null;
    }

    /**
     * The accrual tier that currently applies to this employee (array from
     * config/pto.php), or null if there's no start date.
     */
    public function ptoTier(): ?array
    {
        $years = $this->ptoYearsOfService();
        if ($years === null) {
            return null;
        }

        $higherAtBoundary = config('pto.higher_tier_at_boundary', true);
        $match = null;

        foreach (config('pto.tiers', []) as $tier) {
            $qualifies = $higherAtBoundary
                ? $years >= $tier['min_years']
                : $years > $tier['min_years'] || $tier['min_years'] === 0;

            if ($qualifies) {
                $match = $tier;
            }
        }

        return $match;
    }

    /** Maximum balance (1.5x annual) for the current tier, or null. */
    public function ptoCap(): ?float
    {
        $tier = $this->ptoTier();

        return $tier ? (float) $tier['cap'] : null;
    }

    /** Current PTO balance = signed sum of all ledger entries. */
    public function ptoBalance(): float
    {
        return (float) $this->ptoLedgerEntries()->sum('hours');
    }

    public function hasRole(string $slug): bool
    {
        return $this->role && $this->role->slug === $slug;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(Role::SUPERADMIN_SLUG);
    }

    public function hasPermission(string $slug): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->isSuperAdmin()) {
            return true;
        }

        if (! $this->role || ! $this->role->is_active) {
            return false;
        }

        return $this->role->hasPermission($slug);
    }
}
