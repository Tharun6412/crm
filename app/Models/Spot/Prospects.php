<?php

namespace App\Models\Spot;

use App\Models\Admin\Cluster;
use App\Models\Admin\FirmTypes;
use App\Models\Admin\FuelTypes;
use App\Models\Admin\Ga;
use App\Models\Admin\IndustrialAreas;
use App\Models\Admin\State;
use App\Models\Admin\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'status_id',
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

    /**
     * Relation with GA
     */
    public function ga():BelongsTo
    {
        return $this->belongsTo(Ga::class)->withDefault();
    }
    /**
     * Relation with Cluster
     */
    public function cluster():BelongsTo
    {
        return $this->belongsTo(Cluster::class)->withDefault();
    }
    /**
     * Relation with State
     */
    public function state():BelongsTo
    {
        return $this->belongsTo(State::class)->withDefault();
    }

    /**
     * Relation with Industrial Area
     */
    public function industrialArea() : BelongsTo
    {
        return $this->belongsTo(IndustrialAreas::class, 'industrial_area_id')->withDefault();
    }

    /**
     * Relation with FirmType
     */
    public function firm():BelongsTo
    {
        return $this->belongsTo(FirmTypes::class)->withDefault();
    }
    /**
     * Relation with FuelType
     */
    public function fuelType():BelongsTo
    {
        return $this->belongsTo(FuelTypes::class, 'fuel_id')->withDefault();
    }

    /**
     * Relation with CreatedBy
     */
    public function createdBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
    /**
     * Relation with Stage
     */
    public function stageType():BelongsTo
    {
        return $this->belongsTo(Status::class, 'stage')->withDefault();
    }
    /**
     * Relation with Sub-Stage
     */
    public function subStage():BelongsTo
    {
        return $this->belongsTo(Status::class, 'sub_stage_id')->withDefault();
    }
    /**
     * Relation with Status
     */
    public function statusType():BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id')->withDefault();
    }

    /**
     * Prospect Documents Relation
     */
    public function documents():HasMany
    {
        return $this->hasMany(ProspectDocuments::class, 'prospect_id', 'id')->latest();
    }

    /**
     * Prospect Date Change Requests
     */
    public function dateChangeHistory():HasMany
    {
        return $this->hasMany(ProspectDateChangeRequest::class, 'prospect_id', 'id')->latest();
    }

    /**
     * Prospect Status Relation
     */
    public function statusHistory():HasMany
    {
        return $this->hasMany(ProspectStatusHistory::class, 'prospect_id', 'id')->latest();
    }

    /**
     * Prospect Comments
     */
    public function commentsHistory():HasMany
    {
        return $this->hasMany(ProspectComments::class, 'prospect_id', 'id')->latest();
    }
    /**
     * Prospect PipeLine
     */
    public function PipeLineHistory():HasMany
    {
        return $this->hasMany(ProspectPipeline::class, 'prospect_id', 'id')->latest();
    }
}
