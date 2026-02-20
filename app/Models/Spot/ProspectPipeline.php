<?php

namespace App\Models\Spot;

use App\Models\Admin\User;
use App\Models\Master\PipeTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProspectPipeline extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'spt_prospect_pipeline';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'prospect_id',
        'pipe_type_id',
        'length',
        'status',
        'created_by',
        'updated_by'
    ];

    /**
     * Relation with User
     */
    public function createdBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    /**
     * Relation with USer
     */
    public function updatedBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }

    /**
     * Relation with Pipe Type
     */
    public function pipeType(): BelongsTo
    {
        return $this->belongsTo(PipeTypes::class, 'pipe_type_id', 'id')->withDefault();
    }
}