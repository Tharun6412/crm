<?php

namespace App\Models\Consumer;
use App\Models\Master\District;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DistrictCounter extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cns_district_counter';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'district_id',
        'count',
    ];

    /**
     * Relation with Consumer
     */
    public function district():BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id')->withDefault();
    }
}