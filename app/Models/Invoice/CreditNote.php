<?php

namespace App\Models\Invoice;

use App\Models\Admin\User;
use App\Models\Master\Tax;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class CreditNote extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'bil_credit_notes';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'code',
        'invoice_id',
        'type',
        'notes',
        'base_amount',
        'tax_id',
        'tax_value',
        'tax_amount',
        'total_amount',
        'status',
        'created_by',
    ];

    // Append invNumber to the $fillable
    protected $appends = ['inv_number'];

    /**
     * To Get Invoice Number
     */
    protected function invNumber():Attribute
    {
        return Attribute::get(fn() => "{$this->invoice->invoice_number}");
    }
    /**
     * Relation with credit items
     */
    public function items(): HasMany
    {
        return $this->hasMany(CreditItem::class);
    }

    /**
     * Relation with Invoice
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(BillInvoice::class, 'invoice_id')->withDefault();
    }

    /**
     * Relation with Taxes
     */
    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class, 'tax_id')->withDefault();
    }

    /**
     * Realtion with CreatedBy
     */
    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    /**
     * PolyMorphic Relation to Ledger
     */
    public function ledger():MorphMany
    {
        return $this->morphMany(Ledger::class, 'legible');
    }
}