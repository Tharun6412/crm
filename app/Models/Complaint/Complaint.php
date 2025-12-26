<?php

namespace App\Models\Complaint;

use App\Models\Admin\User;
use App\Models\Consumer\Consumer;
use App\Models\Master\ComplaintCategory;
use App\Models\Master\ComplaintMedia;
use App\Models\Master\ComplaintPriority;
use App\Models\Master\ComplaintSegment;
use App\Models\Master\ComplaintType;
use App\Models\Master\District;
use App\Models\Master\Ga;
use App\Models\Master\MasterComplaintStatus;
use App\Models\Master\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Complaint extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cmp_complaints';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'consumer_id',
        'code',
        'state_id',
        'ga_id',
        'district_id',
        'name',
        'email',
        'phone',
        'category_id',
        'description',
        'segment_id',
        'type_id',
        'media_id',
        'priority_id',
        'estimated_closed_at',
        'closed_at',
        'status_id',
        'created_by',
        'updated_by',
    ];
    /**
     * Casts Dates
     */
    public function casts() {
        return [
            'estimated_closed_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    /**
     * Relation with Segments
     */
    public function segment() :BelongsTo
    {
        return $this->belongsTo(ComplaintSegment::class, 'segment_id')->withDefault();
    }

    /**
     * Relation with Consumer
     */
    public function consumer():BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'consumer_id')->withDefault();
    }

    /**
     * Relation with Category
     */
    public function category():BelongsTo
    {
        return $this->belongsTo(ComplaintCategory::class, 'category_id')->withDefault();
    }
    /**
     * Relation with Media
     */
    public function media():BelongsTo
    {
        return $this->belongsTo(ComplaintMedia::class, 'media_id')->withDefault();
    }
    /**
     * Relation with Priority
     */
    public function priority():BelongsTo
    {
        return $this->belongsTo(ComplaintPriority::class, 'priority_id')->withDefault();
    }

    /**
     * Relation with Status
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(MasterComplaintStatus::class, 'status_id')->withDefault();
    }

    /**
     * Relation with Complaint StatusHistory 
     */
    public function statusHistory():HasMany
    {
        return $this->hasMany(ComplaintStatusHistory::class, 'complaint_id', 'id')->orderBy('created_at', 'desc');
    }
    /**
     * Relation with Type
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(ComplaintType::class, 'type_id')->withDefault();
    }

    /**
     * Realtion with CreatedBy
     */
    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
    /**
     * Realtion with UpdatedBy
     */
    public function updatedBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }

    /**
     * Relation with Complaint Assign
     */
    public function assign():HasOne
    {
        return $this->hasOne(ComplaintAssign::class, 'complaint_id', 'id');
    }

    /**
     * Relation with Complaint Documents
     */
    public function complaintDocuments():HasMany
    {
        return $this->hasMany(ComplaintDocument::class, 'complaint_id', 'id')->orderBy('created_at', 'desc');
    }

    /**
     * Relation with State
     */
    public function state():BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id')->withDefault();
    }

    /**
     * Relation with Ga
     */
    public function ga():BelongsTo
    {
        return $this->belongsTo(Ga::class, 'ga_id')->withDefault();
    }

    /**
     * Relation with District
     */
    public function district():BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id')->withDefault();
    }

    /**
     * Relation with Comments
     */
    public function comments():HasMany
    {
        return $this->hasMany(ComplaintComment::class, 'complaint_id', 'id')->orderBy('created_at', 'desc');
    }

    /**
     * Relation with feedback
     */
    public function feedback():HasOne
    {
        return $this->hasOne(ComplaintFeedback::class, 'complaint_id', 'id');
    }
}