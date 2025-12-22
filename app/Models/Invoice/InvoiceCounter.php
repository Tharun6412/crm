<?php

namespace App\Models\Invoice;

use App\Models\Master\BillInvoiceItem;
use App\Models\Master\State;
use App\Models\Master\Tax;
use App\Models\Master\TaxGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class InvoiceCounter extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'bil_invoice_counter';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'state_id',
        'tax_group_id',
        'invoice_code',
        'count',
    ];

    /**
     * Relation with state
     */
    public function state():BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id')->withDefault();
    }

    /**
     * Relation with Taxgroup
     */
    public function taxGroup(): BelongsTo
    {
        return $this->belongsTo(TaxGroup::class);
    }
}