<?php

namespace App\Models\Invoice;

use App\Models\Admin\User;
use App\Models\Consumer\Consumer;
use App\Models\Master\PaymentTransactionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'pay_transactions';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'consumer_id',
        'invoice_id',
        'order_id',
        'through',
        'amount',
        'status_id',
        'transaction_date',
        'transaction_ref',
        'bank_ref',
        'transaction_no',
        'paid_amount',
        'payment_mode',
        'updated_by',
    ];

    /**
     * Relation with Consumer
     */
    public function consumer() :BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'consumer_id')->withDefault();
    }

    /**
     * Relation with Invoice
     */
    public function invoice() :BelongsTo
    {
        return $this->belongsTo(BillInvoice::class, 'invoice_id')->withDefault();
    }

    /**
     * Relation with user
     */
    public function status():BelongsTo
    {
        return $this->belongsTo(PaymentTransactionStatus::class, 'status_id')->withDefault();
    }

    /**
     * Relation with user
     */
    public function updatedBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }
}