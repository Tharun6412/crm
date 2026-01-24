<?php

namespace App\Models\Consumer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prepaid extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cns_prepaid';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'consumer_id',
        'conversion_date',
        'bonus',
        'bonus_status',
        'bonus_date',
        'balance',
        'balance_date',
        'hes_status',
        'hes_date',
    ];

    // Casting
    protected $casts = [
        'conversion_date' => 'date',
        'bonus_date' => 'date',
        'balance_date' => 'date',
        'hes_date' => 'date',
    ];

    /**
     * Relation with consumers
     */
    public function consumers():BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'consumer_id');
    }
}