<?php

namespace App\Models\Invoice;

use App\Models\Admin\User;
use App\Models\Master\PaymentChequeStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentCheque extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'pay_payment_cheques';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'payment_id',
        'chq_number',
        'chq_date',
        'amount',
        'status_id',
        'created_by',
    ];

    /**
     * Casts
     */
    public function casts() {
        return [
            'chq_date' => 'date',
        ];
    }

    /**
     * Relation with Payment
     */
    public function payment() :BelongsTo
    {
        return $this->belongsTo(InvoicePayment::class, 'payment_id')->withDefault();
    }

    /**
     * Relation with Status
     */
    public function status() :BelongsTo
    {
        return $this->belongsTo(PaymentChequeStatus::class, 'status_id')->withDefault();
    }

    /**
     * Relation with user
     */
    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
}