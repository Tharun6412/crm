<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditItem extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'bil_credit_items';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'credit_note_id',
        'description',
        'quantity',
        'unit_price',
        'total_price',
    ];

    /**
     * Relation with Credit Note
     */
    public function creditNote():BelongsTo
    {
        return $this->belongsTo(CreditNote::class, 'credit_note_id')->withDefault();
    }
}