<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumerSchemeGa extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_cns_scheme_ga';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'scheme_id',
        'ga_id',
    ];

    /**
     * Relation with Scheme
     */
    public function scheme():BelongsTo
    {
        return $this->belongsTo(MasterConsumerScheme::class, 'scheme_id')->withDefault();
    }

    /**
     * Relation with GA
     */
    public function ga():BelongsTo
    {
        return $this->belongsTo(Ga::class, 'ga_id')->withDefault();
    }
}