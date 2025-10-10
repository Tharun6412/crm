<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndustrialAreas extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'adm_industrial_areas';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'ga_id',
        'name',
        'created_by',
    ];

    /**
     * Relation with GA
     */
    public function ga() : BelongsTo
    {
        return $this->belongsTo(Ga::class)->withDefault();
    }
}