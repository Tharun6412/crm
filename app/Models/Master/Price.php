<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_price';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
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
     * Relation with Segments
     */
    public function segment(): BelongsTo
    {
        return $this->belongsTo(Segment::class);
    }

    /**
     * Relatio with district
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Relation with Users
     */
}