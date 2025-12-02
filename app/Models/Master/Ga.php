<?php

namespace App\Models\Master;

use App\Models\Admin\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ga extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_gas';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'code',
        'name',
        'hes_code',
        'state_id',
        'cluster_id',
        'status',
        'position',
        'created_at',
    ];

    /**
     * @return casts
     */
    public function casts()
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    /**
     * Relation with State
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Relation with cluster
     */
    public function cluster(): BelongsTo
    {
        return $this->belongsTo(Cluster::class);
    }

    /**
     * Relation with pivot table user_ga
     */
    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'adm_user_ga', 'ga_id', 'user_id');
    }

    /**
     * Belongs to many relations with schemes
     */
    // public function schemes(): BelongsToMany
    // {
    //     return $this->belongsToMany(ConsumerScheme::class, 'mst_cns_scheme_ga', 'ga_id', 'scheme_id');
    // }

    /**
     * Relation with districts
     */
    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }
}