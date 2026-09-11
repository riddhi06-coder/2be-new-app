<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('incident_reports', 'severity')) {
            Schema::table('incident_reports', function (Blueprint $table) {
                $table->dropColumn('severity');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('incident_reports', 'severity')) {
            Schema::table('incident_reports', function (Blueprint $table) {
                $table->string('severity')->nullable()->after('category');
            });
        }
    }
};
