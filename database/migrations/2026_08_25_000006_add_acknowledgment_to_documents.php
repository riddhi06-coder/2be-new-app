<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (! Schema::hasColumn('documents', 'requires_acknowledgment')) {
                $table->boolean('requires_acknowledgment')->default(false)->after('is_public');
            }
            if (! Schema::hasColumn('documents', 'acknowledgment_due')) {
                $table->date('acknowledgment_due')->nullable()->after('requires_acknowledgment');
            }
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn('documents', 'acknowledgment_due')) {
                $table->dropColumn('acknowledgment_due');
            }
            if (Schema::hasColumn('documents', 'requires_acknowledgment')) {
                $table->dropColumn('requires_acknowledgment');
            }
        });
    }
};
