<?php

namespace App\Models\Lms;

use App\Models\Admin\User;
use App\Models\Master\Area;
use App\Models\Master\Department;
use App\Models\Master\Ga;
use App\Models\Master\MasterConsumerStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DeliveryUnit extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'lms_delivery_units';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
        'manager_id',
        'ga_id',
        'dept_id',
        'responsible_status_id',
        'action_status_id',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Relation with ga
     */
    public function ga(): BelongsTo
    {
        return $this->belongsTo(Ga::class,'ga_id');
    }

    /**
     * Relation with departments
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class,'dept_id');
    }

    /**
     * Relation with users
     */
    public function duIncharge(): BelongsTo
    {
        return $this->belongsTo(User::class,'manager_id');
    }

    /**
     * Relation with users
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class,'created_by');
    }

    /**
     * Relation with users
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class,'created_by');
    }

    /**
     * Relation with Responsible Status
     */
    public function responsibleStatus():BelongsTo
    {
        return $this->belongsTo(MasterConsumerStatus::class, 'responsible_status_id');
    }
    /**
     * Relation with Area
     */
    public function areas(): BelongsToMany
    {
        return $this->belongsToMany(Area::class,'lms_du_areas','du_id','area_id')->withPivot('dept_id');
    }

    /**
     * Relation with Teams
     */
    public function teams():BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'lms_du_teams', 'du_id', 'team_id');
    }
}