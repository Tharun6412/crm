<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillCollectionCenter extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_bill_collection_centers';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'ga_id',
        'name',
        'address',
        'contact_person',
        'contcat_mobile',
        'timings',
    ];

    /**
     * Relation with GA
     */
    public function ga():BelongsTo
    {
        return $this->belongsTo(Ga::class, 'ga_id')->withDefault();
    }
}