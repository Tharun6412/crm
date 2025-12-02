<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * Relation with state
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Relation with CLuster
     */
    public function cluster(): BelongsTo
    {
        return $this->belongsTo(Cluster::class);
    }

    /**
     * Relation with GA
     */
    public function ga(): BelongsTo
    {
        return $this->belongsTo(Ga::class);
    }

    /**
     * Relation with Charge areas
     */
    public function cas(): HasMany
    {
        return $this->hasMany(Ca::class);
    }
}