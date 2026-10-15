<?php

namespace App\Console\Commands;

use App\Services\GoogleReviewService;
use Illuminate\Console\Command;

class SyncGoogleReviews extends Command
{
    protected $signature = 'reviews:sync';

    protected $description = 'Sync Google Business Profile reviews into the app (no-op until API credentials are configured)';

    public function handle(GoogleReviewService $service): int
    {
        if (! $service->isConfigured()) {
            $this->warn('Google Business Profile API is not configured yet — nothing synced. (The portal shows stored/sample reviews.)');
            return self::SUCCESS;
        }

        $count = $service->sync();
        $this->info($count === null ? 'Sync skipped.' : "Synced {$count} review(s).");

        return self::SUCCESS;
    }
}
