<?php

namespace App\Models\Invoice;

use App\Models\Consumer\Consumer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillRecharge extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'bil_recharges';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'consumer_id',
        'transaction_id',
        'amount',
        'created_at',
    ];

    /**
     * Casts
     */
    public function casts() {
        return [
            'created_at' => 'datetime',
        ];
    }
    /**
     * Relation with Consumer
     */
    public function consumer() :BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'consumer_id')->withDefault();
    }
}