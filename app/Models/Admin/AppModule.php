<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppModule extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'adm_app_modules';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
        'code',
        'status',
    ];

    /**
     * Relation with Roles Pivote
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany('adm_role_app_modules', 'app_module_id', 'role_id');
    }
}