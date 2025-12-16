<?php

namespace App\Models\Master;

use App\Models\Admin\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * Casts Dates
     */
    public function casts()
    {
        return [
            'effective_from' => 'date',
            'effective_to' => 'date',
        ];
    }

    /**
     * Relation with Price History
     */
    public function history(): HasMany
    {
        return $this->hasMany(PriceHistory::class)->orderBy('created_at', 'desc');
    }

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
     * Relation with Users created_by
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relation with Users updated_by
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}