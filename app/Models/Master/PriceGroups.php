<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PriceGroups extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_price_groups';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
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

    /**
     * Relation - GA
     */
    public function ga(): BelongsTo
    {
        return $this->belongsTo(Ga::class);
    }

    /**
     * Relation - Segment
     */
    public function segment(): BelongsTo
    {
        return $this->belongsTo(Segment::class);
    }

    /**
     * Relation - History
     */
    public function history(): HasMany
    {
        return $this->hasMany(PriceGroupHistory::class, 'price_group_id');
    }
}