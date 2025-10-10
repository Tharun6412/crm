<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ga extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'adm_ga';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'state_id',
        'cluster_id',
        'code',
        'hes_code',
        'code_backup',
        'name',
        'cng_counter',
        'status',
        'added_at',
        'added_by',
    ];

    /**
     * @return casts
     */
    public function casts()
    {
        return [
            'added_at' => 'datetime',
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
}