<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleAction extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'adm_role_actions';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'role_id',
        'module_action_id',
    ];
}