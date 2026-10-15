<?php

namespace App\Console\Commands;

use App\Mail\QuarterlyAssessmentDueMail;
use App\Models\AdminNotification;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Daily check: when an active employee reaches their 90-day (quarterly)
 * assessment due date, notify every admin via the dashboard bell AND email.
 * Each employee is notified once per cycle — the flag resets when an
 * assessment is marked completed.
 */
class CheckQuarterlyAssessments extends Command
{
    protected $signature = 'assessments:check-due';

    protected $description = 'Notify admins when employees are due for their quarterly (90-day) assessment';

    public function handle(): void
    {
        Log::info('[assessment-check] started');

        $employees = User::whereHas('role', fn ($q) => $q->where('slug', 'employee'))
            ->where('is_active', true)
            ->whereNotNull('start_date')
            ->get();

        // Due now AND not already notified for the current cycle.
        $due = $employees->filter(
            fn (User $e) => $e->isAssessmentDue() && is_null($e->assessment_due_notified_at)
        )->values();

        if ($due->isEmpty()) {
            Log::info('[assessment-check] no employees newly due');
            return;
        }

        // 1) Dashboard bell notification for every admin, per employee.
        foreach ($due as $emp) {
            $dueDate = $emp->nextAssessmentDueDate();

            AdminNotification::notifyAdmins([
                'type'    => 'assessment',
                'title'   => 'Quarterly assessment due',
                'message' => $emp->name.' is due for a quarterly (90-day) assessment'
                            .($dueDate ? ' (since '.$dueDate->format('M j, Y').')' : '').'.',
                'url'     => route('admin.employees.edit', $emp->id),
                'icon'    => 'fa fa-clipboard',
            ]);

            $emp->forceFill(['assessment_due_notified_at' => now()])->save();
        }

        // 2) One summary email to all active admins.
        $adminEmails = User::where('is_active', true)
            ->whereHas('role', fn ($q) => $q->where('slug', '!=', 'employee'))
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (! empty($adminEmails)) {
            try {
                Mail::to($adminEmails)->send(new QuarterlyAssessmentDueMail($due));
                Log::info('[assessment-check] email sent', ['to' => $adminEmails]);
            } catch (\Throwable $e) {
                Log::error('[assessment-check] email failed: '.$e->getMessage());
            }
        } else {
            Log::warning('[assessment-check] no admin email recipients found');
        }

        Log::info('[assessment-check] notified for '.$due->count().' employee(s)');
    }
}
