<?php

namespace App\Models\Consumer;

use App\Models\Admin\User;
use App\Models\Invoice\BillInvoice;
use App\Models\Master\PaymentType;
use App\Models\Master\SdPaymentStatus;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumerSdPayment extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cns_consumer_sd_payments';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'consumer_id',
        'payment_type_id',
        'transaction_number',
        'amount',
        'emi_no',
        'status_id',
        'invoice_id',
        'balance',
        'created_by',
    ];

    /**
     * Relation with Consumer
     */
    public function consumer():BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'consumer_id')->withDefault();
    }

    /**
     * Relation with Payment Type
     */
    public function paymentType() :BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id')->withDefault();
    }

    /**
     * Relation with Status
     */
    public function status() :BelongsTo
    {
        return $this->belongsTo(SdPaymentStatus::class, 'status_id')->withDefault();
    }

    /**
     * Relation with Invoice
     */
    public function invoice():BelongsTo
    {
        return $this->belongsTo(BillInvoice::class, 'invoice_id')->withDefault();
    }
    /**
     * Realtion with CreatedBy
     */
    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
}