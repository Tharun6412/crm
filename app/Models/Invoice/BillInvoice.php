<?php

namespace App\Models\Invoice;

use App\Models\Admin\User;
use App\Models\Consumer\Consumer;
use App\Models\Master\BillInvoiceType;
use App\Models\Master\BillStatus;
use App\Models\Master\Tax;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BillInvoice extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'bil_invoices';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'type_id',
        'consumer_id',
        'invoice_number',
        'invoice_date',
        'base_amount',
        'taxable_amount',
        'tax_id',
        'tax_value',
        'tax_amount',
        'credit_amount',
        'total_amount',
        'paid_amount',
        'balance_amount',
        'due_date',
        'status_id',
        'parent_invoice_id',
        'created_by',
        'updated_by',
    ];

    /**
     * Casts
     */
    public function casts() {
        return [
            'invoice_date' => 'date',
            'due_date' => 'date',
        ];
    }

    /**
     * Relation with Consumer
     */
    public function consumer() :BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'consumer_id')->withDefault();
    }

    /**
     * Relation with items
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

    /**
     * Relation with Type
     */
    public function invoiceType() :BelongsTo
    {
        return $this->belongsTo(BillInvoiceType::class, 'type_id')->withDefault();
    }

    /**
     * Status Relation
     */
    public function status() :BelongsTo
    {
        return $this->belongsTo(BillStatus::class, 'status_id')->withDefault();
    }

    /**
     * Tax Relation
     */
    public function tax() :BelongsTo
    {
        return $this->belongsTo(Tax::class, 'tax_id')->withDefault();
    }

    /**
     * Relation with user
     */
    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    /**
     * Relation with user
     */
    public function updatedBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }

    /**
     * Invoice table has HasMany relation with invoice consumption
     * 
     */
    public function consumption():HasMany
    {
        return $this->hasMany(BillInvoiceConsumption::class, 'invoice_id');
    }
}