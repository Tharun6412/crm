<?php

namespace App\Models\Spot;

use Illuminate\Database\Eloquent\Model;

class ProspectPipeline extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'spot_prospect_pipeline';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'prospect_id',
        'pipe_type',
        'length',
        'status',
        'created_by',
    ];
}