<?php

namespace App\Models\Spot;

use Illuminate\Database\Eloquent\Model;

class ProspectStatusHistory extends Model
{
    /**
     * The table associated with the model
     * @var string
     */
    protected $table = 'spot_status_history';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'prospect_id',
        'status_id',
        'sub_status_id',
        'notes',
        'created_at',
        'created_by',
    ];

    /**
     * Timestamps False
     */
    public $timestamps= false;
}