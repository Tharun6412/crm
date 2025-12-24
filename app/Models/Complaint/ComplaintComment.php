<?php

namespace App\Models\Complaint;

use App\Models\Admin\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ComplaintComment extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cmp_complaint_comments';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'complaint_id',
        'comments',
        'commentable_type',
        'commentable_id',
    ];

    /**
     * Relation with Complaint
     */
    public function complaint() :BelongsTo
    {
        return $this->belongsTo(Complaint::class, 'complaint_id')->withDefault();
    }

    /**
     * Polymorphic relation
     * Can be User or Consumer
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo(); //user||consumer
    }
}