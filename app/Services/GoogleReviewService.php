<?php

namespace App\Services;

use App\Models\GoogleReview;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Syncs Google Business Profile reviews into the local `google_reviews` table.
 *
 * Until the client grants access + Google approves API access, `isConfigured()`
 * is false and the portal simply shows whatever is in the table (the sample set
 * loaded via `reviews:seed-sample`). Once the five .env values are filled in,
 * `sync()` pulls the real reviews — no other code changes are needed.
 */
class GoogleReviewService
{
    /** Google rating enum -> integer. */
    private const STAR_MAP = [
        'ONE' => 1, 'TWO' => 2, 'THREE' => 3, 'FOUR' => 4, 'FIVE' => 5,
    ];

    /** True once the Business Profile API credentials are present. */
    public function isConfigured(): bool
    {
        $c = config('google_reviews') ?? config('services.google_reviews');

        return ! empty($c['client_id'])
            && ! empty($c['client_secret'])
            && ! empty($c['refresh_token'])
            && ! empty($c['account_id'])
            && ! empty($c['location_id']);
    }

    /**
     * Fetch reviews from Google and upsert them. Returns the number of reviews
     * processed, or null when not yet configured (nothing to do).
     */
    public function sync(): ?int
    {
        if (! $this->isConfigured()) {
            Log::info('[google-reviews] skipped — API not configured yet (showing stored/sample data)');
            return null;
        }

        try {
            $reviews = $this->fetchFromBusinessProfile();
        } catch (\Throwable $e) {
            Log::error('[google-reviews] fetch failed: '.$e->getMessage());
            return null;
        }

        foreach ($reviews as $r) {
            GoogleReview::updateOrCreate(
                ['external_id' => $r['external_id']],
                $r,
            );
        }

        Log::info('[google-reviews] synced '.count($reviews).' review(s)');

        return count($reviews);
    }

    /**
     * Call the Google Business Profile API: exchange the refresh token for an
     * access token, then list the location's reviews and map them to our shape.
     *
     * @return array<int,array<string,mixed>>
     */
    private function fetchFromBusinessProfile(): array
    {
        $c = config('services.google_reviews');

        // 1) Refresh token -> short-lived access token.
        $tokenRes = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id'     => $c['client_id'],
            'client_secret' => $c['client_secret'],
            'refresh_token' => $c['refresh_token'],
            'grant_type'    => 'refresh_token',
        ])->throw()->json();

        $accessToken = $tokenRes['access_token'] ?? null;
        if (! $accessToken) {
            throw new \RuntimeException('No access_token returned from Google.');
        }

        // 2) List reviews for the location (paginated).
        $account  = $c['account_id'];   // accounts/xxx
        $location = $c['location_id'];  // locations/xxx
        $base     = "https://mybusiness.googleapis.com/v4/{$account}/{$location}/reviews";

        $out       = [];
        $pageToken = null;

        do {
            $res = Http::withToken($accessToken)
                ->get($base, array_filter([
                    'pageSize'  => 50,
                    'pageToken' => $pageToken,
                ]))
                ->throw()
                ->json();

            foreach (($res['reviews'] ?? []) as $rev) {
                $out[] = $this->mapReview($rev);
            }

            $pageToken = $res['nextPageToken'] ?? null;
        } while ($pageToken);

        return $out;
    }

    /** Map one Google review payload to our table columns. */
    private function mapReview(array $rev): array
    {
        return [
            'external_id'        => $rev['reviewId'] ?? ($rev['name'] ?? null),
            'reviewer_name'      => $rev['reviewer']['displayName'] ?? 'Google user',
            'reviewer_photo_url' => $rev['reviewer']['profilePhotoUrl'] ?? null,
            'rating'             => self::STAR_MAP[$rev['starRating'] ?? 'FIVE'] ?? 5,
            'comment'            => $rev['comment'] ?? null,
            'reviewed_at'        => isset($rev['createTime']) ? \Illuminate\Support\Carbon::parse($rev['createTime']) : null,
            'reply_comment'      => $rev['reviewReply']['comment'] ?? null,
            'replied_at'         => isset($rev['reviewReply']['updateTime']) ? \Illuminate\Support\Carbon::parse($rev['reviewReply']['updateTime']) : null,
            'source'             => 'google',
        ];
    }

    /**
     * Load a set of realistic sample reviews so the portal UI can be seen before
     * the live Google feed is connected. Idempotent (keyed by external_id).
     */
    public function seedSamples(): int
    {
        $samples = [
            ['external_id' => 'sample-1', 'reviewer_name' => 'Michael Thompson',  'rating' => 5, 'days' => 4,   'comment' => 'Bob’s crew showed up right on time and pumped our cesspool quickly and cleanly. Super professional and friendly — highly recommend!'],
            ['external_id' => 'sample-2', 'reviewer_name' => 'Keoni Kahale',       'rating' => 5, 'days' => 11,  'comment' => 'Best septic service on the Big Island. Fair pricing and they explained everything clearly. Will definitely use again.', 'reply' => 'Mahalo Keoni! We appreciate your business and kind words.'],
            ['external_id' => 'sample-3', 'reviewer_name' => 'Sarah Williams',     'rating' => 4, 'days' => 20,  'comment' => 'Great service and very responsive. Took a little while to schedule during the busy season but well worth the wait.'],
            ['external_id' => 'sample-4', 'reviewer_name' => 'David Nakamura',     'rating' => 5, 'days' => 33,  'comment' => 'Called them for an emergency and they came out same day. Lifesavers! The technician was knowledgeable and courteous.'],
            ['external_id' => 'sample-5', 'reviewer_name' => 'Lisa Anderson',      'rating' => 5, 'days' => 47,  'comment' => 'Reliable, honest, and professional. We’ve been using 2B Environmental for years and they never disappoint.'],
            ['external_id' => 'sample-6', 'reviewer_name' => 'James Paʻahao',      'rating' => 5, 'days' => 62,  'comment' => 'Quick, clean, and reasonably priced. The office staff was helpful and friendly when booking. Five stars!'],
            ['external_id' => 'sample-7', 'reviewer_name' => 'Megan Carter',       'rating' => 4, 'days' => 78,  'comment' => 'Good experience overall. The team was careful around our landscaping and cleaned up after themselves.'],
            ['external_id' => 'sample-8', 'reviewer_name' => 'Robert Kealoha',     'rating' => 5, 'days' => 95,  'comment' => 'Outstanding service from start to finish. Honest assessment, no upselling, just solid work. Highly recommended.'],
        ];

        foreach ($samples as $s) {
            GoogleReview::updateOrCreate(
                ['external_id' => $s['external_id']],
                [
                    'reviewer_name' => $s['reviewer_name'],
                    'rating'        => $s['rating'],
                    'comment'       => $s['comment'],
                    'reviewed_at'   => now()->subDays($s['days']),
                    'reply_comment' => $s['reply'] ?? null,
                    'replied_at'    => isset($s['reply']) ? now()->subDays($s['days'] - 1) : null,
                    'source'        => 'sample',
                    'is_visible'    => true,
                ],
            );
        }

        return count($samples);
    }
}
