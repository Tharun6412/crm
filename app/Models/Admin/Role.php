<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    /**
     * Relation with pivot table user_roles
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'adm_user_roles', 'role_id', 'user_id');
    }

    /**
     * Role with app modules Pivote relation
     */
    public function appModules(): BelongsToMany
    {
        return $this->belongsToMany(AppModule::class, 'adm_role_app_modules', 'role_id', 'app_module_id');
    }
}