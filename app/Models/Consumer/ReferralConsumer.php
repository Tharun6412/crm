<?php

namespace App\Models\Consumer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReferralConsumer extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cns_referral_consumers';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'request_id',
        'status',
        'referral_consumer_id',
        'redeem_amount',
        'redeem_date',
    ];

    

    /**
     * Relation with consumers
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(ReferralRequest::class, 'request_id');
    }

    /**
     * Relation with consumers
     */
    public function consumers(): BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'referral_consumer_id');
    }
}