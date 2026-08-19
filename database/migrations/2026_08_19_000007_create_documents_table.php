<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_category_id')->constrained('document_categories')->cascadeOnDelete();
            $table->string('title');
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();

            // Access model: public docs are visible to everyone; personal docs
            // (is_public = false) belong to a single employee via user_id.
            $table->boolean('is_public')->default(true);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();

            $table->foreignId('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
