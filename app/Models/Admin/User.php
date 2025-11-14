<?php

namespace App\Models\Admin;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Master\Department;
use App\Models\Master\Ga;
use App\Models\Spot\SpotRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
        // Additional Columns
        'first_name',
        'last_name',
        'status',
        'email_verified_at',
        'mobile',
        'emp_id',
        'mobile_b',
        'gender',
        'dob',
        'image',
        'type',
        'department_id'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'dob' => 'date',
            'password' => 'hashed',
        ];
    }

    /**
     * Relation with User roles
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Relation with roles table via pivote user_roles
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'adm_user_roles', 'user_id', 'role_id');
    }

    /**
     * Relation with geo areas table via pivote user_ga
     */
    public function ga(): BelongsToMany
    {
        return $this->belongsToMany(Ga::class, 'adm_user_ga', 'user_id', 'ga_id');
    }

    /**
     * Relation with Spot Roles table via pivot spot_user_roles
     */
    public function spotRoles():BelongsToMany
    {
        return $this->belongsToMany(SpotRoles::class, 'spot_user_roles', 'user_id', 'spot_role_id');
    }

    /**
     * Status history
     */
    public function statusHistory(): HasMany
    {
        return $this->hasMany(UserStatus::class);
    }
}
