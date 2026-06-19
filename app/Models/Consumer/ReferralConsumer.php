<?php

namespace App\Models\Consumer;

use App\Models\Payments\PayAdvanceTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
        'referral_consumer_id',
        'status',
        'referrer_amount',
        'referrer_redeem_date',
        'referrer_redeem_status',
        'referral_amount',
        'referral_redeem_date',
        'referral_redeem_status',
    ];

    

    /**
     * Relation with consumers
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(Referral::class, 'request_id');
    }

    /**
     * Relation with consumers
     */
    public function consumer(): BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'referral_consumer_id');
    }
    /**
     * #PolyMorphic relation
     * Relation with Advance Transaction
     * MorphMany
     */
    public function advance():MorphMany
    {
        return $this->morphMany(PayAdvanceTransaction::class, 'advancable');
    }
}