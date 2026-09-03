<?php

namespace App\Models;

use App\Models\Concerns\TracksDeletedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WasteDisposalDetails extends Model
{
    use HasFactory, SoftDeletes, TracksDeletedBy;

    protected $table = 'waste_disposal_details';
    public $timestamps = false;

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

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
