<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('google_reviews')) {
            return;
        }

        Schema::create('google_reviews', function (Blueprint $table) {
            $table->id();
            // Google's own review id — used to de-duplicate on each sync.
            $table->string('external_id')->nullable()->unique();
            $table->string('reviewer_name');
            $table->string('reviewer_photo_url')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);   // 1..5
            $table->text('comment')->nullable();
            $table->timestamp('reviewed_at')->nullable();        // when the customer left it
            $table->text('reply_comment')->nullable();           // owner's reply, if any
            $table->timestamp('replied_at')->nullable();
            $table->string('source')->default('google');
            $table->boolean('is_visible')->default(true);        // admin can hide a review
            $table->timestamps();

            $table->index(['is_visible', 'reviewed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('google_reviews');
    }
};
