<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add a descriptive column comment documenting each role id.
        // Using MODIFY keeps the existing foreign key intact (type is unchanged).
        DB::statement("ALTER TABLE `users` MODIFY `role_id` BIGINT UNSIGNED NULL COMMENT 'FK to roles.id: 1=Super Admin, 2=Admin, 3=Employee'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `role_id` BIGINT UNSIGNED NULL COMMENT ''");
    }
};
