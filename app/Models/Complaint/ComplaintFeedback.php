<?php

namespace App\Models\Complaint;

use App\Models\Admin\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplaintComment extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cmp_complaint_feedback';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'complaint_id',
        'rating',
        'notes',
        'collected_by',
        'link',
    ];

    /**
     * Relation with Complaint
     */
    public function complaint() :BelongsTo
    {
        return $this->belongsTo(Complaint::class, 'complaint_id')->withDefault();
    }

    /**
     * Realtion with CollectedBy
     */
    public function collectedBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'collected_by')->withDefault();
    }
}