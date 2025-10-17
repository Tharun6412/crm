<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'adm_roles';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
        'position',
        'status',
    ];

    /**
     * Relation with roleActions
     */
    public function roleActions(): HasMany
    {
        return $this->hasMany(RoleAction::class);
    }

    /**
     * Relation with role actions for SYNC
     */
    public function actions(): BelongsToMany
    {
        return $this->belongsToMany(ModuleAction::class, 'adm_role_actions', 'role_id', 'module_action_id');
    }
}