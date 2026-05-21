<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('septic_system_details', function (Blueprint $table) {
            $table->id();

            // ── Client / Device Info ─────────────────────────────────────────
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('browser', 100)->nullable();
            $table->string('browser_version', 50)->nullable();
            $table->string('device_type', 50)->nullable();
            $table->string('operating_system', 100)->nullable();

            // ── Geolocation ──────────────────────────────────────────────────
            $table->string('location_country', 100)->nullable();
            $table->string('location_city', 100)->nullable();
            $table->string('location_region', 100)->nullable();
            $table->string('location_timezone', 100)->nullable();

            // ── Step 1 : Basic Information ───────────────────────────────────
            $table->string('inspection_type')->nullable();   // "Home Inspector, Realtor"
            $table->date('date_of_pickup')->nullable();
            $table->string('time', 50)->nullable();
            $table->string('weather', 100)->nullable();
            $table->string('inspector_name_company')->nullable();
            $table->text('site_address')->nullable();
            $table->string('tax_map_number')->nullable();
            $table->string('type_of_system')->nullable();

            // ── Step 2 : Site Observations ───────────────────────────────────
            $table->text('property_in_use')->nullable();     // comma-separated labels
            $table->text('site_conditions')->nullable();     // comma-separated labels
            $table->string('surface_runoff', 10)->nullable(); // Yes | No | N/A
            $table->text('malfunction')->nullable();          // comma-separated labels

            // ── Step 3 : System Evaluation ───────────────────────────────────
            $table->string('manhole_accessible', 10)->nullable();       // Yes | No
            $table->string('lid_needs_repair', 10)->nullable();         // Yes | No
            $table->string('liquid_operating_level')->nullable();        // human-readable
            $table->string('scum_layer_thickness')->nullable();
            $table->string('sludge_layer_thickness')->nullable();
            $table->string('tank_pumping_recommended', 10)->nullable(); // Yes | No
            $table->string('tank_pumped', 10)->nullable();              // Yes | No | N/A
            $table->string('approx_volume_pumped')->nullable();
            $table->text('water_stream_from_house')->nullable();        // comma-separated
            $table->text('water_stream_from_drain')->nullable();        // comma-separated
            $table->string('inlet_tee_needs_repair', 10)->nullable();  // Yes | N/D
            $table->string('outlet_tee_needs_repair', 10)->nullable(); // Yes | N/D
            $table->string('tank_composition')->nullable();
            $table->string('approx_tank_size')->nullable();
            $table->string('service_recommended', 10)->nullable();     // Yes | No | N/D
            $table->text('comments')->nullable();
            $table->string('inspector_signature')->nullable();
            $table->text('notes')->nullable();

            // ── Draft Management ─────────────────────────────────────────────
            $table->boolean('is_draft')->default(false);
            $table->string('session_key', 100)->nullable()->index();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('inserted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('septic_system_details');
    }
};
