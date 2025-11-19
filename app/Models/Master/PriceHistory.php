<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceHistory extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_price_history';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'price_id',
        'segment_id',
        'district_id',
        'basic',
        'supply',
        'margin',
        'basic_price',
        'tax_id',
        'tax_value',
        'tax_price',
        'rsp',
        'effective_from',
        'effective_to',
        'created_by',
        'updated_by',
    ];

    /**
     * Relation with Price
     */
    public function price() : BelongsTo
    {
        return $this->belongsTo(Price::class, 'price_id')->withDefault();
    }

    /**
     * Relation with Segment
     */
    public function segment() : BelongsTo
    {
        return $this->belongsTo(Segment::class, 'segment_id')->withDefault();
    }

    /**
     * Relation with District
     */
    public function district() : BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id')->withDefault();
    }

    /**
     * Relation with Tax
     */
    public function tax():BelongsTo
    {
        return $this->belongsTo(Tax::class, 'tax_id')->withDefault();
    }
}