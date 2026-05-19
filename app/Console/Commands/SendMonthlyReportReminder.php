<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\MonthlyReportReminderMail;
use App\Models\EmailSettingsDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class SendMonthlyReportReminder extends Command
{
    protected $signature = 'email:monthly-report-reminder';
    protected $description = 'Send reminder email on last day of month';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */


    /**
     * The console command description.
     *
     * @var string
     */

    /**
     * Execute the console command.
     */

    public function handle()
    {
        // Get Hawaii time explicitly
        $today = Carbon::now('Pacific/Honolulu');
    
        Log::info('CRON HIT AT (Server Time): ' . now());
        Log::info('CRON HIT AT (Hawaii Time): ' . $today);
        Log::info('MonthlyReportReminder command started.');
    
        try {
    
            Log::info('Current Hawaii time: ' . $today);
    
    
            Log::info('Fetching email settings...');
            $emailSetting = EmailSettingsDetails::wherenull('deleted_by')->first();
    
            if (!$emailSetting) {
                Log::error('No email settings found in DB.');
                return;
            }
    
            Log::info('Email settings fetched successfully.');
    
            $emails = collect([
                $emailSetting->default_email,
                $emailSetting->email1,
                $emailSetting->email2,
                $emailSetting->email3,
            ])->filter()->toArray();
    
            Log::info('Emails to send:', $emails);
    
            if (empty($emails)) {
                Log::error('No valid email addresses found.');
                return;
            }
    
            Log::info('Sending mail...');
    
            Mail::to($emails)->send(new \App\Mail\MonthlyReportReminderMail());
    
            Log::info('Mail sent successfully.');
    
        } catch (\Exception $e) {
    
            Log::error('Error while sending monthly reminder: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
        }
    
        Log::info('MonthlyReportReminder command ended.');
    }
}
