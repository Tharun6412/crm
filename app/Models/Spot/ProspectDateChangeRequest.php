<?php

namespace App\Models\Spot;

use App\Models\DocumentCentre\Documents;
use App\Models\Admin\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProspectDateChangeRequest extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'spt_prospect_date_change_history';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'prospect_id',
        'current_date',
        'new_date',
        'note',
        'status',
        'win',
        'created_by',
        'created_at',
        'approved_by',
        'approved_at',
    ];

    /**
     * @return casts
     */
    public function casts()
    {
        return [
            'current_date' => 'datetime',
            'new_date' => 'datetime',
            'created_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Timestamps False
     */
    public $timestamps= false;

    /**
     * Relation with User
     */
    public function createdBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    /**
     * Relation with User
     */
    public function approvedBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by')->withDefault();
    }

    /**
     * Relation with prospects
     */
    public function prospects() : BelongsTo
    {
        return $this->belongsTo(Prospects::class, 'prospect_id')->withDefault();
    }
}