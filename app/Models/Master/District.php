<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_districts';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'code',
        'name',
        'display_name',
        'cluster_id',
        'state_id',
        'ga_id',
        'status',
        'created_by',
    ];
}