<?php

namespace App\Models\Consumer;

use App\Models\Master\ConsumerSchemePayment;
use App\Models\Master\MasterConsumerScheme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumerScheme extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cns_consumer_schemes';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'consumer_id',
        'scheme_id',
        'scheme_payment_id',
        'security_deposit',
        'consumption_deposit',
        'total_deposit',
        'emi_amount',
        'rental_amount',
        'paid_deposit',
        'balance',
        'status',
    ];

    /**
     * Relation with Consumer
     */
    public function consumer():BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'consumer_id')->withDefault();
    }

    /**
     * Relation with Status
     */
    public function scheme() :BelongsTo
    {
        return $this->belongsTo(MasterConsumerScheme::class, 'scheme_id')->withDefault();
    }

    /**
     * Relation with Payment Status
     */
    public function schemePayment() :BelongsTo
    {
        return $this->belongsTo(ConsumerSchemePayment::class, 'scheme_payment_id')->withDefault();
    }
}