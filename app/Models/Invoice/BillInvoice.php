<?php

namespace App\Models\Invoice;

use App\Models\Admin\User;
use App\Models\Consumer\Consumer;
use App\Models\Master\BillInvoiceType;
use App\Models\Master\BillStatus;
use App\Models\Master\Tax;
use App\Models\Payments\PayAdvanceTransaction;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
        'total_amount',
        'advance_amount',
        'credit_amount',
        'payable_amount',
        'paid_amount',
        'balance_amount',
        'due_date',
        'status_id',
        'parent_invoice_id',
        'created_by',
        'updated_by',
        's_paid_amount',
        's_balance_amount',
        's_payment_status',
        'iteration_balance',
        'reconciliation_flag'
    ];

    /**
     * Casts
     */
    public function casts() {
        return [
            'invoice_date' => 'date:Y-m-d',
            'due_date' => 'date:Y-m-d',
        ];
    }

    protected $appends = ['inv_number'];

    protected function invNumber(): Attribute
    {
        return Attribute::get(fn() => "{$this->invoice_number}");
    }

    /**
     * Relation with Consumer
     */
    public function consumer(): BelongsTo
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
    public function invoiceType(): BelongsTo
    {
        return $this->belongsTo(BillInvoiceType::class, 'type_id')->withDefault();
    }

    /**
     * Status Relation
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(BillStatus::class, 'status_id')->withDefault();
    }

    /**
     * Tax Relation
     */
    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class, 'tax_id')->withDefault();
    }

    /**
     * Relation with user
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    /**
     * Relation with user
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }

    /**
     * Invoice table has HasOne relation with invoice consumption
     * 
     */
    public function consumption(): HasOne
    {
        return $this->hasOne(BillInvoiceConsumption::class, 'invoice_id');
    }

    /**
     * Relation with Credit notes
     */
    public function creditNotes(): HasMany
    {
        return $this->hasMany(CreditNote::class, 'invoice_id')->orderBy('created_at', 'desc');
    }
    
    /**
     * Each invoice may have multiple payments
     * 
     */
    public function payments(): HasMany
    {
        return $this->hasMany(InvoicePayment::class, 'invoice_id');
    }

    /**
     * Parent invoice (original invoice)
     */
    public function parentInvoice(): BelongsTo
    {
        return $this->belongsTo(BillInvoice::class, 'parent_invoice_id');
    }

    /**
     * Child invoices (late fee, penalty, adjustments, etc.)
     */
    public function childInvoices(): HasMany
    {
        return $this->hasMany(self::class, 'parent_invoice_id')->orderBy('id', 'desc');
    }

    /**
     * PolyMorphic Relation to Ledger
     */
    public function ledger(): MorphMany
    {
        return $this->morphMany(Ledger::class, 'legible');
    }

    /**
     * #PolyMorphic relation
     * Relation with Advance Transaction
     * MorphMany
     */
    public function advance():MorphMany
    {
        return $this->morphMany(PayAdvanceTransaction::class, 'advancable');
    }
}