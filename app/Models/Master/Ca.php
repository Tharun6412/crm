<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ca extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_cas';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'code',
        'name',
        'ga_id',
        'district_id',
        'status',
    ];

    /**
     * Relation with GA
     */
    public function ga() : BelongsTo
    {
        return $this->belongsTo(Ga::class)->withDefault();
    }
}