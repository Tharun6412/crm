<?php

namespace App\Models\Spot;

use App\Models\Admin\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SpotRoles extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'spot_roles';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Relation with Pivot Table spot_user_roles
     */
    public function spotUsers():BelongsToMany
    {
        return $this->belongsToMany(User::class, 'spot_user_roles', 'role_id', 'user_id');
    }
}