<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cesspool_system_details', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('notes');
            $table->string('video_path')->nullable()->after('image_path');
        });

        Schema::table('septic_system_details', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('notes');
            $table->string('video_path')->nullable()->after('image_path');
        });
    }

    public function down(): void
    {
        Schema::table('cesspool_system_details', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'video_path']);
        });

        Schema::table('septic_system_details', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'video_path']);
        });
    }
};
