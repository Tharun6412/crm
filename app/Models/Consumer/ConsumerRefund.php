<?php

namespace App\Models\Consumer;

use App\Models\Admin\User;
use App\Models\Invoice\BillInvoice;
use App\Models\Master\PaymentType;
use App\Models\Master\RefundStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConsumerRefund extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'ref_refunds';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'consumer_id',
        'request_no',
        'sd_paid',
        'outstanding_amount',
        'disconnection_amount',
        'invoice_id',
        'refund_amount',
        'payment_type_id',
        'transaction_id',
        'transaction_date',
        'status_id',
        'created_by',
    ];

    /**
     * Casts
     */
    public function casts() {
        return [
            'transaction_date' => 'date',
        ];
    }
    /**
     * Relation with Consumer
     */
    public function consumer():BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'consumer_id')->withDefault();
    }

    /**
     * Relation with Invoice
     */
    public function invoice():BelongsTo
    {
        return $this->belongsTo(BillInvoice::class, 'invoice_id')->withDefault();
    }

    /**
     * Relation with PayType
     */
    public function paymentType():BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    /**
     * Relation with Status
     */
    public function status():BelongsTo
    {
        return $this->belongsTo(RefundStatus::class, 'status_id');
    }

    /**
     * Relation with CreatedBy
     */
    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    /**
     * Relation with Refund history Status
     */
    public function refundStatus():HasMany
    {
        return $this->hasMany(ConsumerRefundStatus::class, 'refund_id')->orderBy('created_at', 'desc');
    }

}