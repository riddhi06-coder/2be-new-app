<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSettingsDetails extends Model
{
    use HasFactory;

    protected $table = 'email_settings';
    public $timestamps = false;

    protected $fillable = [
        'default_email',
        'email1',
        'email2',
        'email3',

        'inserted_at',
        'inserted_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];
}
