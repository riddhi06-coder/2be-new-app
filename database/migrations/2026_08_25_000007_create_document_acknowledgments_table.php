<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('document_acknowledgments')) {
            return;
        }

        Schema::create('document_acknowledgments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('signed_name');           // typed full name = the signature
            $table->string('ip_address', 45)->nullable();
            $table->string('signed_pdf_path')->nullable(); // stamped copy filed under the employee
            $table->timestamp('acknowledged_at');
            $table->timestamps();

            $table->unique(['document_id', 'user_id']); // one acknowledgment per employee per document
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_acknowledgments');
    }
};
