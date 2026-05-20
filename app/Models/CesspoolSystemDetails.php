<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CesspoolSystemDetails extends Model
{
    use HasFactory;

    protected $table = 'cesspool_system_details';
    public $timestamps = false;

    protected $fillable = [
        'ip_address',

        // Step 1: Basic Information
        'home_inspection',
        'realtor',
        'routine',
        'date_of_pickup',
        'inspector_name_company',
        'site_address',
        'tax_map_number',
        'type_of_system',

        // Step 2: Site Observations
        'property_in_use_yes',
        'property_in_use_no',
        'site_condition_grass',
        'site_condition_system_area',
        'site_condition_other_area',
        'site_condition_ponding',
        'site_condition_barriers',
        'site_condition_effective',
        'site_condition_not_effective',
        'runoff_yes',
        'runoff_no',
        'runoff_na',
        'malfunction_yes',
        'malfunction_no',
        'discharge_grey',
        'discharge_black',
        'discharge_unknown',
        'discharge_cesspool_area',
        'discharge_cesspool_edge',
        'discharge_bleed_out',
        'discharge_past_failure',

        // Step 3: System Evaluation
        'access_lids_yes',
        'access_lids_no',
        'access_lid_repair_yes',
        'access_lid_repair_no',
        'cesspool_water_level_depth',
        'pumping_recommended_yes',
        'pumping_recommended_no',
        'cesspool_pumped',
        'water_stream_from_house',
        'inlet_pipe_needs_repair',
        'cesspool_composition',
        'service_recommended',
        'comments',
        'notes',
        'inspector_signature',
        'print_name',
        'date',

        'inserted_at',
    ];

    protected $casts = [
        'home_inspection'             => 'boolean',
        'realtor'                     => 'boolean',
        'routine'                     => 'boolean',
        'property_in_use_yes'         => 'boolean',
        'property_in_use_no'          => 'boolean',
        'site_condition_grass'        => 'boolean',
        'site_condition_system_area'  => 'boolean',
        'site_condition_other_area'   => 'boolean',
        'site_condition_ponding'      => 'boolean',
        'site_condition_barriers'     => 'boolean',
        'site_condition_effective'    => 'boolean',
        'site_condition_not_effective'=> 'boolean',
        'runoff_yes'                  => 'boolean',
        'runoff_no'                   => 'boolean',
        'runoff_na'                   => 'boolean',
        'malfunction_yes'             => 'boolean',
        'malfunction_no'              => 'boolean',
        'discharge_grey'              => 'boolean',
        'discharge_black'             => 'boolean',
        'discharge_unknown'           => 'boolean',
        'discharge_cesspool_area'     => 'boolean',
        'discharge_cesspool_edge'     => 'boolean',
        'discharge_bleed_out'         => 'boolean',
        'discharge_past_failure'      => 'boolean',
        'access_lids_yes'             => 'boolean',
        'access_lids_no'              => 'boolean',
        'access_lid_repair_yes'       => 'boolean',
        'access_lid_repair_no'        => 'boolean',
        'pumping_recommended_yes'     => 'boolean',
        'pumping_recommended_no'      => 'boolean',
        'date_of_pickup'              => 'date',
        'date'                        => 'date',
    ];
}
