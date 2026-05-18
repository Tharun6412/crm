<?php

namespace App\Models\Lms;

use App\Http\Controllers\Lms\LeadController;
use App\Models\Admin\User;
use App\Models\Master\Area;
use App\Models\Master\Ca;
use App\Models\Master\District;
use App\Models\Master\Ga;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    protected $table = 'lms_leads';
    protected $fillable = [
        'code',
        'name',
        'mobile',
        'address_line1',
        'address_line2',
        'owner_ship',
        'lpg_service',
        'ga_id',
        'district_id',
        'ca_id',
        'area_id',
        'lead_channel_id',
        'status_id',
        'created_by',
    ];
    /**
     * Relation with ga 
     */
    public function ga() : BelongsTo
    {
        return $this->BelongsTo(Ga::class,'ga_id');
    }
    /**
     * Realation with district
     */
    public function district() : BelongsTo
    {
        return $this->belongsTo(District::class,'district_id');
    }
    /**
     * Relation with charge area
     */
    public function ca() : BelongsTo
    {
        return $this->belongsTo(Ca::class,'ca_id');
    }
    /**
     * Realation with area
     */
    public function area() : BelongsTo
    {
        return $this->belongsTo(Area::class,'area_id');
    }
    /**
     * Realation with user
     */
    public function createdBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
    /**
     * Relation this lead status
     */
    public function status() : BelongsTo
    {
        return $this->belongsTo(LeadStatus::class,'status_id');
    }
    /**
     * Realation with Status History
     */
    public function statushistory():HasMany
    {
        return $this->hasMany(LeadActivities::class,'lead_id');
    }
    /**
     * Relation with lead Channels
     */
    public function leadChannel(): BelongsTo
    {
        return $this->belongsTo(LeadChannel::class,'lead_channel_id');
    }
}
