<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ca extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_cas';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'code',
        'name',
        'ga_id',
        'district_id',
        'status',
    ];

    /**
     * Relation with GA
     */
    public function ga() : BelongsTo
    {
        return $this->belongsTo(Ga::class, 'ga_id')->withDefault();
    }

    /**
     * Relation with District
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    /**
     * Relation with areas
     */
    public function areas(): HasMany
    {
        return $this->hasMany(Area::class);
    }
}