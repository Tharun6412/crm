<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class FuelTypes extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'adm_png_fuel_types';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
        'position',
        'spot',
        'fuel_group',
        'created_by',
        'status',
    ];
}