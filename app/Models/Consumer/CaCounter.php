<?php

namespace App\Models\Consumer;

use App\Models\Master\Ca;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaCounter extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cns_ca_counter';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'ca_id',
        'count',
    ];

    /**
     * Relation with Charge Area
     */
    public function ca():BelongsTo
    {
        return $this->belongsTo(Ca::class, 'ca_id')->withDefault();
    }
}