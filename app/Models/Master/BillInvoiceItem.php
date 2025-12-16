<?php

namespace App\Models\Master;

use App\Models\Admin\User;
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
        'type_id',
        'code',
        'name',
        'hsn',
        'price_type',
        'basic',
        'tax_value',
        'price',
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

    /**
     * Relation with User
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relation with User
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}