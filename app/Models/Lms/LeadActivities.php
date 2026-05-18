<?php

namespace App\Models\Lms;

use App\Models\Admin\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadActivities extends Model
{
    protected $table = 'lms_lead_activities';
    protected $fillable = [
        'lead_id',
        'lead_channel_id',
        'lead_status_id',
        'notes',
        'created_by',
    ];
    /**
     * Relation with lead
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class,'lead_id');
    }
    /**
     * Relation with lead status
     */
    public function leadStatus(): BelongsTo
    {
        return $this->belongsTo(LeadStatus::class,'lead_status_id');
    }
    /**
     * Relation with created by
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class,'created_by');
    }
    /**
     * Relation with lead channels
     */
    public function leadChannel(): BelongsTo
    {
        return $this->belongsTo(LeadChannel::class,'lead_channel_id');
    }
}
