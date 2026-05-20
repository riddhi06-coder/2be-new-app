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
        // Client info
        'ip_address',
        'user_agent',
        'browser',
        'browser_version',
        'device_type',
        'operating_system',
        'location_country',
        'location_city',
        'location_region',
        'location_timezone',

        // Step 1
        'inspection_type',
        'date_of_pickup',
        'inspector_name_company',
        'site_address',
        'tax_map_number',
        'type_of_system',

        // Step 2
        'property_in_use',
        'site_conditions',
        'surface_runoff',
        'malfunction',
        'surface_discharge',

        // Step 3
        'accessible_lids',
        'access_lid_repair',
        'cesspool_water_level_depth',
        'pumping_recommended',
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

        // Draft management
        'is_draft',
        'session_key',
        'expires_at',
        'inserted_at',
    ];

    protected $casts = [
        'date_of_pickup' => 'date',
        'date'           => 'date',
        'is_draft'       => 'boolean',
        'expires_at'     => 'datetime',
        'inserted_at'    => 'datetime',
    ];
}
