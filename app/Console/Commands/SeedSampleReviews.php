<?php

namespace App\Console\Commands;

use App\Services\GoogleReviewService;
use Illuminate\Console\Command;

class SeedSampleReviews extends Command
{
    protected $signature = 'reviews:seed-sample';

    protected $description = 'Load a set of sample Google reviews so the portal UI can be previewed before the live feed is connected';

    public function handle(GoogleReviewService $service): int
    {
        $count = $service->seedSamples();
        $this->info("Loaded {$count} sample review(s).");

        return self::SUCCESS;
    }
}
