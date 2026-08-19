<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
        });

        // Backfill slugs for any existing announcements.
        foreach (DB::table('announcements')->get() as $row) {
            $base = Str::slug($row->title) ?: 'announcement';
            $slug = $base;
            $i    = 1;
            while (DB::table('announcements')->where('slug', $slug)->where('id', '!=', $row->id)->exists()) {
                $slug = $base.'-'.(++$i);
            }
            DB::table('announcements')->where('id', $row->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
