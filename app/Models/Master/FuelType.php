<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class FuelType extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_fuel_types';

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