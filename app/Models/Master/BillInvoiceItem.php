<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillInvoiceItem extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_bil_invoice_items';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'code',
        'name',
        'price_type',
        'price',
        'type_id',
        'hsn',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Relation with type
     */
    public function type() : BelongsTo
    {
        return $this->belongsTo(BillInvoiceItemType::class, 'type_id')->withDefault();
    }
}