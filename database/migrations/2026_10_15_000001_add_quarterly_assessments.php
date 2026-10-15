<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'start_date')) {
                $table->date('start_date')->nullable()->after('phone');
            }
            if (! Schema::hasColumn('users', 'last_assessment_at')) {
                // Date of the most recent completed quarterly assessment (null = never).
                $table->date('last_assessment_at')->nullable()->after('start_date');
            }
            if (! Schema::hasColumn('users', 'assessment_due_notified_at')) {
                // When the "assessment due" reminder was last sent for the current cycle
                // (reset to null whenever an assessment is completed) — prevents daily spam.
                $table->timestamp('assessment_due_notified_at')->nullable()->after('last_assessment_at');
            }
        });

        if (! Schema::hasTable('quarterly_assessments')) {
            Schema::create('quarterly_assessments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->date('assessed_on');
                $table->foreignId('assessed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('quarterly_assessments');

        Schema::table('users', function (Blueprint $table) {
            foreach (['start_date', 'last_assessment_at', 'assessment_due_notified_at'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
