<?php

namespace App\Models\Complaint;

use App\Models\Admin\User;
use App\Models\Master\MasterComplaintStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplaintStatusHistory extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cmp_complaint_status';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'complaint_id',
        'status_id',
        'notes',
        'created_by',
    ];

    /**
     * Relation with Complaint
     */
    public function complaint() :BelongsTo
    {
        return $this->belongsTo(Complaint::class, 'complaint_id')->withDefault();
    }

    /**
     * Relation with Status
     */
    public function status() :BelongsTo
    {
        return $this->belongsTo(MasterComplaintStatus::class, 'status_id');
    }

    /**
     * Realtion with CreatedBy
     */
    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}