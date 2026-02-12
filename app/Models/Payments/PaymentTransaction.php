<?php

namespace App\Models\Payments;

use App\Models\Admin\User;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\InvoicePayment;
use App\Models\Master\PaymentGateway;
use App\Models\Master\PaymentModule;
use App\Models\Master\PaymentTransactionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'payment_module_id',
        'consumer_id',
        'invoice_id',
        'gateway_id',
        'transaction_date',
        'transaction_id',
        'amount',
        'transaction_status_id',
        'transaction_ref',
        'bank_ref',
        'pg_ref_id',
        'paid_amount',
        'payment_mode',
        'updated_by',
    ];

    /**
     * Casting
     */
    protected $casts = [
        'transaction_date' => 'date',
    ];

    /**
     * Relation with Consumer
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(PaymentModule::class, 'payment_module_id')->withDefault();
    }

    /**
     * Relation with Consumer
     */
    public function gateway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class, 'gateway_id')->withDefault();
    }

    /**
     * Relation with Consumer
     */
    public function consumer(): BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'consumer_id')->withDefault();
    }

    /**
     * Relation with Invoice
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(BillInvoice::class, 'invoice_id')->withDefault();
    }

    /**
     * Relation with transaction status
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(PaymentTransactionStatus::class, 'transaction_status_id')->withDefault();
    }

    /**
     * Relation with user
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }

    /**
     * Relation with Payments
     */
    public function payment(): HasOne
    {
        return $this->hasOne(InvoicePayment::class, 'pay_transaction_id', 'id');
    }
}