<?php

namespace App\Models\Spot;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'spot_status';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
        'type',
        'parent',
    ];
}