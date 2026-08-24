<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incident_reports', function (Blueprint $table) {
            // Who raised the report: 'employee' = submitted via the portal,
            // 'admin' = created in the backend by an admin.
            $table->string('source')->default('admin')->after('status')
                ->comment("'employee' = raised via portal, 'admin' = created in backend");
        });
    }

    public function down(): void
    {
        Schema::table('incident_reports', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};
