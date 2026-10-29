<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'accrues_pto')) {
                // Regular full-time employees accrue PTO; temp/seasonal/intern/PT<30h do not.
                $table->boolean('accrues_pto')->default(true)->after('start_date');
            }
        });

        if (! Schema::hasTable('pto_ledger_entries')) {
            Schema::create('pto_ledger_entries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                // allotment | carryover | usage | adjustment
                $table->string('type')->default('adjustment');
                // signed DAYS: + credits (allotment/carryover/positive adjustment), - debits (usage)
                $table->decimal('days', 7, 2);
                $table->date('effective_date');
                $table->string('note')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index(['user_id', 'effective_date']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pto_ledger_entries');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'accrues_pto')) {
                $table->dropColumn('accrues_pto');
            }
        });
    }
};
