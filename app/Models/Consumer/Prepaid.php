<?php

namespace App\Models\Consumer;

use Illuminate\Database\Eloquent\Model;

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
    ];

    // Casting
    protected $casts = [
        'conversion_date' => 'date',
        'bonus_date' => 'date',
        'balance_date' => 'date',
    ];
}