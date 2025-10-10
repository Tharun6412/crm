<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
class FirmTypes extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'adm_png_firm_types';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
        'status',
        'created_by',
    ];
}