<?php

namespace App\Models\Consumer;

use App\Models\Master\LpgOmc;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumerData extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cns_consumer_data';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'consumer_id',
        'lat',
        'lng',
        'lpg_consumer_number',
        'lpg_id',
        'lpg_omc_id',
        'registered_mobile',
        'lpg_connections',
        'kyc_status',
        'reference_code',
        'referrer_consumer_id',
    ];

    /**
     * Relation with Consumer
     */
    public function consumer():BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'consumer_id')->withDefault();
    }

    /**
     * Referred By
     */
    public function referredBy(): BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'referrer_consumer_id');
    }
    /**
     * Relation omc types
     */
    public function omcType(): BelongsTo
    {
        return $this->belongsTo(LpgOmc::class,'lpg_omc_id');
    }
}