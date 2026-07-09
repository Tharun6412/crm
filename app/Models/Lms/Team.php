<?php

namespace App\Models\Lms;

use App\Models\Admin\User;
use App\Models\Consumer\Consumer;
use App\Models\Master\Area;
use App\Models\Master\Ca;
use App\Models\Master\Department;
use App\Models\Master\Ga;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    protected $table = 'lms_teams';
    protected $fillable = [
        'name',
        'ga_id',
        'department_id',
        'du_id',
        'status',
        'responsible_user_id',
        'created_by',
    ];
    /**
     * Relation with ga
     */
    public function ga(): BelongsTo
    {
        return $this->belongsTo(Ga::class,'ga_id');
    }
    /**
     * Relation with charge Area
     */
    public function cas(): BelongsToMany
    {
        return $this->belongsToMany(Ca::class,'lms_team_cas','team_id','ca_id');
    }

    /**
     * Relation with Areas
     */
    public function areas(): BelongsToMany
    {
        return $this->belongsToMany(Area::class,'lms_team_areas','team_id','area_id');
    }

    /**
     * Relation with departments
     */
    public function departments(): BelongsTo
    {
        return $this->belongsTo(Department::class,'department_id');
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
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'lms_team_users','team_id','user_id');
    }
    /**
     * Relation with user
     */
    public function responsibleUser() : BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }
    /**
     * Relation with Consumer
     */
    public function consumer(): BelongsTo
    {
        return $this->belongsTo(Consumer::class,'consumer_id');
    }

    /**
     * Relation with DU
     */
    public function deliveryUnit() : BelongsTo
    {
        return $this->belongsTo(DeliveryUnit::class, 'du_id');
    }
}

