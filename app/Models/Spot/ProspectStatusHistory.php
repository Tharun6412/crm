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
    protected $table = 'spot_status_history';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'prospect_id',
        'status_id',
        'sub_status_id',
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
    public function status():BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id')->withDefault();
    }
    /**
     * Relation with Sub Status
     */
    public function subStatus(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'sub_status_id')->withDefault();
    }
    /**
     * Relation with User
     */
    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
}