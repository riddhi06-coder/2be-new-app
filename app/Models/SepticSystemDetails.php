<?php

namespace App\Models;

use App\Models\Concerns\TracksDeletedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SepticSystemDetails extends Model
{
    use HasFactory, SoftDeletes, TracksDeletedBy;

    protected $table = 'septic_system_details';
    public $timestamps = false;

    protected $fillable = [
        // Client info
        'ip_address', 'user_agent', 'browser', 'browser_version',
        'device_type', 'operating_system',
        'location_country', 'location_city', 'location_region', 'location_timezone',

        // Step 1
        'inspection_type', 'date_of_pickup', 'time', 'weather',
        'inspector_name_company', 'site_address', 'tax_map_number', 'type_of_system',

        // Step 2
        'property_in_use', 'site_conditions', 'surface_runoff', 'malfunction',

        // Step 3
        'manhole_accessible', 'lid_needs_repair', 'liquid_operating_level',
        'scum_layer_thickness', 'sludge_layer_thickness',
        'tank_pumping_recommended', 'tank_pumped', 'approx_volume_pumped',
        'water_stream_from_house', 'water_stream_from_drain',
        'inlet_tee_needs_repair', 'outlet_tee_needs_repair',
        'tank_composition', 'approx_tank_size', 'service_recommended',
        'comments', 'inspector_signature', 'notes',

        // Media
        'image_path', 'video_path',

        // Draft management
        'is_draft', 'session_key', 'expires_at', 'inserted_at',
    ];

    protected $casts = [
        'date_of_pickup' => 'date',
        'is_draft'       => 'boolean',
        'expires_at'     => 'datetime',
        'inserted_at'    => 'datetime',
        'deleted_at'     => 'datetime',
    ];
}
