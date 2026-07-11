<?php

namespace App\Models\Payments;

use App\Models\Admin\User;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\InvoicePayment;
use App\Models\Master\PaymentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PayAdvanceTransaction extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'pay_advance_transactions';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'advancable_type',
        'advancable_id',
        'amount',
        'balance',   
        'transaction_no',     
    ];

    /**
     * Casts
     */
    public function casts() {
        return [
            'updated_at' => 'datetime',
        ];
    }
    /**
     * Relation with Consumer
     */
    public function payment() :BelongsTo
    {
        return $this->belongsTo(InvoicePayment::class, 'payment_id');
    }

    /**
     * PolyMorph Relation
     * Relation with Advancable
     * @instance of Invoice|Payments model
     */
    public function advancable():MorphTo
    {
        return $this->morphTo(); //Invoice|Payment
    }
}