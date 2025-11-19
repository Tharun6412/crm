<?php

namespace App\Models\Invoice;

use App\Models\Admin\User;
use App\Models\DocumentCentre\Documents;
use App\Models\Master\PriceHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillInvoiceConsumption extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'bil_invoice_consumption';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'invoice_id',
        'meter_id',
        'price_history_id',
        'date_from',
        'date_to',
        'days',
        'prev_reading',
        'curr_reading',
        'consumption',
        'cf',
        'meter_change_id',
        'old_consumption',
        'net_consumption',
        'unit_price',
        'total_price',
        'file_id',
    ];

    /**
     * Relation with Invoice
     */
    public function invoice():BelongsTo
    {
        return $this->belongsTo(BillInvoice::class, 'invoice_id')->withDefault();
    }

    /**
     * Realtion with File
     */
    public function file():BelongsTo
    {
        return $this->belongsTo(Documents::class, 'file_id')->withDefault();
    }

    /**
     * Relation with Price History
     */
    public function priceHistory() : BelongsTo
    {
        return $this->belongsTo(PriceHistory::class, 'price_history_id')->withDefault();
    }

    /**
     * Relation with Meter Change
     */
}