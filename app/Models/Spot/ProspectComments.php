<?php

namespace App\Models\Spot;

use App\Models\DocumentCentre\Documents;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProspectComments extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'spot_prospect_comments';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'prospect_id',
        'comments',
        'created_by',
        'created_at',
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
     * Relation with User
     */
    public function createdBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
}