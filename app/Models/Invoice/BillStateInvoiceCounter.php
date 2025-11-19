<?php

namespace App\Models\Invoice;

use App\Models\Master\BillInvoiceItem;
use App\Models\Master\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillStateInvoiceCounter extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'bil_state_invoice_counter';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'state_id',
        'invoice_type',
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
}