<?php

namespace App\Models\Invoice;

use App\Models\Master\PriceHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillInvoiceConsumptionDetails extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'bil_invoice_consumption_details';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'invoice_consumption_id',
        'price_history_id',
        'days',
        'consumption',
        'cf',
        'unit_price',
        'total_price',
    ];

    /**
     * Relation with Invoice
     */
    public function invoiceConsumption():BelongsTo
    {
        return $this->belongsTo(BillInvoiceConsumption::class, 'invoice_consumption_id')->withDefault();
    }

    /**
     * Relation with Price History
     */
    public function priceHistory() : BelongsTo
    {
        return $this->belongsTo(PriceHistory::class, 'price_history_id')->withDefault();
    }
}