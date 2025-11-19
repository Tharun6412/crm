<?php

namespace App\Models\Consumer;

use App\Models\Admin\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumerSdReversals extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cns_consumer_sd_reversals';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'consumer_sd_payment_id',
        'reason',
        'created_by',
    ];

    /**
     * Relation with Consumer
     */
    public function sdPayment():BelongsTo
    {
        return $this->belongsTo(ConsumerSdPayment::class, 'consumer_sd_payment_id')->withDefault();
    }

    /**
     * Realtion with CreatedBy
     */
    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
}