<?php

namespace App\Models\Spot;

use App\Models\Admin\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProspectStatusHistory extends Model
{
    /**
     * The table associated with the model
     * @var string
     */
    protected $table = 'spt_prospect_status_history';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'prospect_id',
        'stage_id',
        'notes',
        'created_at',
        'created_by',
    ];
    /**
     * @return casts
     */
    public function casts()
    {
        return [
            'created_at' => 'datetime',
        ];
    }
    /**
     * Timestamps False
     */
    public $timestamps= false;
    /**
     * Relation with Status
     */
    public function stage():BelongsTo
    {
        return $this->belongsTo(Stage::class, 'stage_id')->withDefault();
    }
    /**
     * Relation with User
     */
    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
}