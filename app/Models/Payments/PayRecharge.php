<?php

namespace App\Models\Payments;

use App\Models\Admin\User;
use App\Models\Consumer\Consumer;
use App\Models\Master\PaymentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayRecharge extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'pay_recharges';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'consumer_id',
        'recharge_date',
        'amount',
        'balance',
        'payment_type_id',
        'transaction_id',
        'status_id',
        'hes_status',
        'hes_date',
        'note',
        'created_by',
        'updated_by',
        'remarks',
    ];

    /**
     * Casts
     */
    public function casts() {
        return [
            'recharge_date' => 'date',
        ];
    }
    /**
     * Relation with Consumer
     */
    public function consumer() :BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'consumer_id')->withDefault();
    }

    /**
     * Relation with Payment Type
     */
    public function paymentType() :BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    /**
     * Relation with user
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}