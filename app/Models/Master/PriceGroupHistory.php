<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceGroupHistory extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_price_group_history';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'price_group_id',
        'code',
        'description',
        'ga_id',
        'segment_id',
        'basic',
        'vat',
        'price',
        'effective_from',
        'created_by',
        'updated_by',
    ];

    /**
     * Casts Dates
     */
    public function casts()
    {
        return [
            'effective_from' => 'date',
        ];
    }
}