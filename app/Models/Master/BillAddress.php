<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillAddress extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_bil_address';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'ga_id',
        'line1',
        'line2',
        'city',
        'district_id',
        'state_id',
        'pincode',
        'created_by',
        'updated_by',
    ];

    /**
     * Relation with District
     */
    public function district():BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id')->withDefault();
    }

    /**
     * Relation with GA
     */
    public function ga():BelongsTo
    {
        return $this->belongsTo(Ga::class, 'ga_id')->withDefault();
    }

    /**
     * Relation with State
     */
    public function state():BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id')->withDefault();
    }
}