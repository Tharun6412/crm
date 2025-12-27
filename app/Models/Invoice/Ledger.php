<?php

namespace App\Models\Invoice;

use App\Models\Consumer\Consumer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Ledger extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'bil_ledger';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'consumer_id',
        'legible_id',
        'legible_type',
        'description',
        'credit',
        'debit',
        'balance',
    ];

    /**
     * Relation with Consumer
     */
    public function consumer() :BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'consumer_id')->withDefault();
    }

    /**
     * Polymorph relation
     */
    public function legible():MorphTo
    {
        return $this->morphTo(); //Invoice|Payments|CreditNote
    }
}