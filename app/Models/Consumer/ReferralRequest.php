<?php

namespace App\Models\Consumer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReferralRequest extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cns_referral_requests';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'consumer_id',
        'name',
        'phone',
    ];

    

    /**
     * Relation with consumers
     */
    public function consumer(): BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'consumer_id');
    }

    /**
     * Relation with consumers
     */
    public function referralConsumers(): HasMany
    {
        return $this->hasMany(ReferralConsumer::class, 'request_id', 'id');
    }
}