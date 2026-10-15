<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('email:monthly-report-reminder')
    ->lastDayOfMonth('09:00')
    ->timezone('Pacific/Honolulu');

// Daily check: notify admins (dashboard bell + email) when an employee reaches
// their 90-day quarterly assessment due date.
Schedule::command('assessments:check-due')
    ->dailyAt('09:00')
    ->timezone('Pacific/Honolulu');

// Daily pull of Google Business Profile reviews into the app (no-op until the
// Google API credentials are configured in .env).
Schedule::command('reviews:sync')
    ->dailyAt('06:00')
    ->timezone('Pacific/Honolulu');
