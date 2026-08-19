<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Timestamp of when the welcome/credentials email was successfully
            // sent. Null = not sent yet (so it can still be triggered later).
            $table->timestamp('welcome_email_sent_at')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('welcome_email_sent_at');
        });
    }
};
