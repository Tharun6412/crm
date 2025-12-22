<?php

namespace App\Models\Invoice;

use App\Models\Master\BillInvoiceItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'bil_invoice_items';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'invoice_id',
        'item_id',
        'description',
        'quantity',
        'unit_price',
        'total_price',
    ];

    /**
     * Relation with Invoice
     */
    public function invoice():BelongsTo
    {
        return $this->belongsTo(BillInvoice::class, 'invoice_id')->withDefault();
    }

    /**
     * Relation with Price History
     */
    public function item() :BelongsTo
    {
        return $this->belongsTo(BillInvoiceItem::class, 'item_id')->withDefault();
    }
}