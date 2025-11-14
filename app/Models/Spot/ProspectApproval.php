<?php

namespace App\Models\Spot;

use Illuminate\Database\Eloquent\Model;

class ProspectApproval extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'spt_prospect_approval';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'prospect_id',
        'status_id',
        'status',
        'notes',
        'created_at',
        'created_by',
    ];

    /**
     * Timestamps False
     */
    public $timestamps= false;
}