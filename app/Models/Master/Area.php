<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Area extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_areas';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
        'ca_id',
        'status',
    ];

    /**
     * Relation with CA
     */
    public function ca():BelongsTo
    {
        return $this->belongsTo(Ca::class, 'ca_id')->withDefault();
    }
}