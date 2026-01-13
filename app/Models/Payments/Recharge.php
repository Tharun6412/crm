<?php

namespace App\Models\Payments;

use App\Models\Admin\User;
use App\Models\Master\PaymentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recharge extends Model
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
        'created_by',
        'updated_by',
    ];

    // Casting
    protected $casts = [
        'recharge_date' => 'date',
    ];

    /**
     * Relatio with Payment Type
     */
    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
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