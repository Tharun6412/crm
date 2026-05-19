<?php

namespace App\Models\Admin;

use App\Models\Admin\User;
use App\Models\Master\Ca;
use App\Models\Master\Department;
use App\Models\Master\Ga;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    protected $table = 'adm_teams';
    protected $fillable = [
        'name',
        'ga_id',
        'department_id',
        'created_by',
    ];

    public function ga(): BelongsTo
    {
        return $this->belongsTo(Ga::class,'ga_id');
    }

    public function cas(): BelongsToMany
    {
        return $this->belongsToMany(Ca::class,'adm_team_cas','team_id','ca_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class,'department_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'adm_team_users','team_id','user_id');
    }
    
}

