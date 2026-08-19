<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // These now hold rich-text (CKEditor) HTML, so widen from varchar to text.
        Schema::table('roles', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });

        Schema::table('document_categories', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->string('description')->nullable()->change();
        });

        Schema::table('document_categories', function (Blueprint $table) {
            $table->string('description')->nullable()->change();
        });
    }
};
