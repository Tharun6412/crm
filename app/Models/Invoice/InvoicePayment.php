<?php

namespace App\Models\Invoice;

use App\Models\Admin\User;
use App\Models\Master\PaymentStatus;
use App\Models\Master\PaymentTransactionStatus;
use App\Models\Master\PaymentType;
use App\Models\Payments\PaymentTransaction;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class InvoicePayment extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'pay_invoice_payments';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'code',
        'invoice_id',
        'payment_date',
        'payment_type_id',
        'transaction_id',
        'pay_transaction_id',
        'amount',
        'balance',
        'status_id',
        'notes',
        'created_by',
    ];

    /**
     * Casts
     */
    public function casts() {
        return [
            'payment_date' => 'date:Y-m-d',
        ];
    }
    
    protected $appends = ['inv_number'];
    
    /**
     * Relation with Invoice
     */
    public function invoice() :BelongsTo
    {
        return $this->belongsTo(BillInvoice::class, 'invoice_id')->withDefault();
    }
    
    
    protected function invNumber():Attribute
    {
        return Attribute::get(fn () => "{$this->invoice->invoice_number}");
    }

    /**
     * Relation with Payment Type
     */
    public function paymentType() :BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id')->withDefault();
    }

    /**
     * Relation with Transaction
     */
    public function transaction() :BelongsTo
    {
        return $this->belongsTo(PaymentTransactionStatus::class, 'transaction_id')->withDefault();
    }

    /**
     * Relation with user
     */
    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    /**
     * Relation with Status
     */
    public function status():BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'status_id')->withDefault();
    }

    /**
     * PolyMorphic Relation to Ledger
     */
    public function ledger():MorphMany
    {
        return $this->morphMany(Ledger::class, 'legible');
    }

    /**
     * Relation with transaction table
     */
    public function payTransaction(): BelongsTo
    {
        return $this->belongsTo(PaymentTransaction::class, 'pay_transaction_id');
    }
}