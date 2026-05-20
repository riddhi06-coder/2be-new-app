<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cesspool_system_details', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address')->nullable();

            // Step 1: Basic Information
            $table->boolean('home_inspection')->default(false);
            $table->boolean('realtor')->default(false);
            $table->boolean('routine')->default(false);
            $table->date('date_of_pickup')->nullable();
            $table->string('inspector_name_company')->nullable();
            $table->string('site_address')->nullable();
            $table->string('tax_map_number')->nullable();
            $table->string('type_of_system')->nullable();

            // Step 2: Site Observations
            $table->boolean('property_in_use_yes')->default(false);
            $table->boolean('property_in_use_no')->default(false);
            $table->boolean('site_condition_grass')->default(false);
            $table->boolean('site_condition_system_area')->default(false);
            $table->boolean('site_condition_other_area')->default(false);
            $table->boolean('site_condition_ponding')->default(false);
            $table->boolean('site_condition_barriers')->default(false);
            $table->boolean('site_condition_effective')->default(false);
            $table->boolean('site_condition_not_effective')->default(false);
            $table->boolean('runoff_yes')->default(false);
            $table->boolean('runoff_no')->default(false);
            $table->boolean('runoff_na')->default(false);
            $table->boolean('malfunction_yes')->default(false);
            $table->boolean('malfunction_no')->default(false);
            $table->boolean('discharge_grey')->default(false);
            $table->boolean('discharge_black')->default(false);
            $table->boolean('discharge_unknown')->default(false);
            $table->boolean('discharge_cesspool_area')->default(false);
            $table->boolean('discharge_cesspool_edge')->default(false);
            $table->boolean('discharge_bleed_out')->default(false);
            $table->boolean('discharge_past_failure')->default(false);

            // Step 3: System Evaluation
            $table->boolean('access_lids_yes')->default(false);
            $table->boolean('access_lids_no')->default(false);
            $table->boolean('access_lid_repair_yes')->default(false);
            $table->boolean('access_lid_repair_no')->default(false);
            $table->string('cesspool_water_level_depth')->nullable();
            $table->boolean('pumping_recommended_yes')->default(false);
            $table->boolean('pumping_recommended_no')->default(false);
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

            $table->timestamp('inserted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cesspool_system_details');
    }
};
