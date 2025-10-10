<?php

namespace App\Models\Spot;

use Illuminate\Database\Eloquent\Model;

class Prospects extends Model
{
    // Table Name
    protected $table = 'spot_prospects';
    protected $fillable = [
        'code',
        'name',
        'firm_id',
        'fuel_id',
        'fuel_consumption',
        'unit_id',
        'potential',
        'expected_date',
        'state_id',
        'cluster_id',
        'ga_id',
        'industrial_area_id',
        'zone',
        'latitude',
        'longitude',
        'ga_head',
        'cluster_head',
        'segment_id',
        'pipeline_availability',
        'notes',
        'stage',
        'sub_stage_id',
        'status',
        'status_date',
        'created_by',
    ];

    /**
     * @return casts
     */
    public function casts()
    {
        return [
            'expected_date' => 'date',
            'status_date' => 'datetime',
        ];
    }
}
