<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class ConnectionType extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_connection_types';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
    ];
}