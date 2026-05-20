<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('cesspool_system_details');

        Schema::create('cesspool_system_details', function (Blueprint $table) {
            $table->id();

            // ── Client / Device Info ─────────────────────────────────────────
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('browser', 100)->nullable();
            $table->string('browser_version', 50)->nullable();
            $table->string('device_type', 50)->nullable();       // Desktop | Mobile | Tablet
            $table->string('operating_system', 100)->nullable();

            // ── Geolocation (from IP) ────────────────────────────────────────
            $table->string('location_country', 100)->nullable();
            $table->string('location_city', 100)->nullable();
            $table->string('location_region', 100)->nullable();
            $table->string('location_timezone', 100)->nullable();

            // ── Step 1 : Basic Information ───────────────────────────────────
            // Comma-separated human-readable values: "Home Inspector, Realtor"
            $table->string('inspection_type')->nullable();
            $table->date('date_of_pickup')->nullable();
            $table->string('inspector_name_company')->nullable();
            $table->string('site_address')->nullable();
            $table->string('tax_map_number')->nullable();
            $table->string('type_of_system')->nullable();

            // ── Step 2 : Site Observations ───────────────────────────────────
            $table->string('property_in_use', 10)->nullable();   // Yes | No
            // Comma-separated: "Grass cover/vegetation condition, Surface Ponding"
            $table->text('site_conditions')->nullable();
            $table->string('surface_runoff', 10)->nullable();    // Yes | No | N/A
            $table->string('malfunction', 10)->nullable();       // Yes | No
            // Comma-separated: "Grey water, Black water"
            $table->text('surface_discharge')->nullable();

            // ── Step 3 : System Evaluation ───────────────────────────────────
            $table->string('accessible_lids', 10)->nullable();      // Yes | No
            $table->string('access_lid_repair', 10)->nullable();    // Yes | No
            $table->string('cesspool_water_level_depth')->nullable();
            $table->string('pumping_recommended', 10)->nullable();  // Yes | No
            $table->string('cesspool_pumped')->nullable();
            $table->string('water_stream_from_house')->nullable();
            $table->string('inlet_pipe_needs_repair')->nullable();
            $table->string('cesspool_composition')->nullable();
            $table->string('service_recommended')->nullable();
            $table->text('comments')->nullable();
            $table->text('notes')->nullable();
            $table->string('inspector_signature')->nullable();
            $table->string('print_name')->nullable();
            $table->date('date')->nullable();

            // ── Draft Management ─────────────────────────────────────────────
            $table->boolean('is_draft')->default(false);
            $table->string('session_key', 100)->nullable()->index();
            $table->timestamp('expires_at')->nullable();

            $table->timestamp('inserted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cesspool_system_details');
    }
};
