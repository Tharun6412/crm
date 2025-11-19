<?php

namespace App\Models\Invoice;

use App\Models\Admin\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentReversal extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'pay_payment_reversals';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'payment_id',
        'notes',
        'created_by',
    ];

    /**
     * Relation with Payment
     */
    public function payment() :BelongsTo
    {
        return $this->belongsTo(InvoicePayment::class, 'payment_id')->withDefault();
    }

    /**
     * Relation with user
     */
    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
}