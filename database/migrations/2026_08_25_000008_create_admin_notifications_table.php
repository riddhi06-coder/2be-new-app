<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('admin_notifications')) {
            return;
        }

        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // recipient (admin)
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete(); // employee who acted
            $table->string('actor_name')->nullable();
            $table->string('type', 40)->default('general'); // incident | document | cesspool | septic | disposal
            $table->string('title');
            $table->string('message', 500)->nullable();
            $table->string('url')->nullable();
            $table->string('icon', 60)->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};
