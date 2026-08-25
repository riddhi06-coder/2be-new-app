<?php

namespace App\Providers;

use App\Observers\ActivityObserver;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /** Models whose create/update/delete actions are recorded in the activity log. */
    private const AUDITED_MODELS = [
        \App\Models\Document::class,
        \App\Models\DocumentCategory::class,
        \App\Models\Announcement::class,
        \App\Models\IncidentReport::class,
        \App\Models\CalendarEvent::class,
        \App\Models\User::class,
        \App\Models\Role::class,
        \App\Models\Permission::class,
        \App\Models\EmailSettingsDetails::class,
        \App\Models\WasteDisposalDetails::class,
        \App\Models\CesspoolSystemDetails::class,
        \App\Models\SepticSystemDetails::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Password-reset emails link to the employee reset page (frontend).
        ResetPassword::createUrlUsing(function ($user, string $token) {
            return route('frontend.employee_reset_password', [
                'token' => $token,
                'email' => $user->getEmailForPasswordReset(),
            ]);
        });

        // Auto-record activity (create/update/delete) across all audited modules.
        foreach (self::AUDITED_MODELS as $model) {
            $model::observe(ActivityObserver::class);
        }
    }
}
