<?php

namespace App\Models\Master;

use App\Models\Admin\User;
use App\Models\Consumer\CaCounter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    /**
     * Relation with cns ca counter
     */
    public function consumerCounter(): HasOne
    {
        return $this->hasOne(CaCounter::class, 'ca_id');
    }

    /**
     * Relation with Pivot Table ad_user_ca
     */
    public function user() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'adm_user_ca', 'ca_id', 'user_id');
    }
}