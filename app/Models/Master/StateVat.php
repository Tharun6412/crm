<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StateVat extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_state_vat';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'state_id',
        'vat',
    ];

    /**
     * Relation with State
     */
    public function state() : BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}