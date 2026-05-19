<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteDisposalDetails extends Model
{
    use HasFactory;

    protected $table = 'waste_disposal_details';
    public $timestamps = false;

    protected $fillable = [
        'ip_address',
        'date_of_pickup',
        'generator_name',
        'waste_type',
        'address',
        'volume_pumped',
        'unit',
        'zip',
        'date_of_discharge',
        'discharge_site',
        'transporter_name',
        'vehicle_license_number',
        'inserted_at',
        'inserted_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];
}
