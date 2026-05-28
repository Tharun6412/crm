<?php

namespace App\Models\Admin;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Complaint\ComplaintComment;
use App\Models\Complaint\ComplaintFeedback;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerSdPayment;
use App\Models\Invoice\InvoicePayment;
use App\Models\Master\Ca;
use App\Models\Master\Department;
use App\Models\Master\Designation;
use App\Models\Master\Ga;
use App\Models\Spot\SpotRoles;
use App\Models\Traits\HasRoles;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'emp_id',
        'password',
        'first_name',
        'last_name',
        'mobile',
        'email',
        'status_id',
        'email_verified_at',
        'mobile_b',
        'gender',
        'dob',
        'doj',
        'image',
        'type',
        'type_id',
        'department_id',
        'designation_id',
        'activated_at',
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
     * New property name = fname + lname
     */
    protected $appends = ['name'];
    protected function name(): Attribute
    {
        return Attribute::get(fn () => "{$this->first_name} {$this->last_name}");
    }

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
            'doj' => 'date',
            'activated_at' => 'datetime',
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
     * Relation with User status
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(UserStatus::class);
    }

    /**
     * Status history
     */
    public function statusHistory(): HasMany
    {
        return $this->hasMany(UserStatusHistory::class);
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
     * Relation with charge area table pivote user_ca
     */
    public function cas(): BelongsToMany
    {
        return $this->belongsToMany(Ca::class,'adm_user_ca','user_id','ca_id');
    }
    /**
     * Relation with user type
     */
    public function employeeType(): BelongsTo
    {
        return $this->belongsTo(UserType::class,'type_id');
    }

    /**
     * Poly Morph Relation with Complaint comments
     */
    public function commentsBy(): MorphMany
    {
        return $this->morphMany(ComplaintComment::class, 'commentable');
    }

    /**
     * PolyMorph Relation with complaint Feedback
     */
    public function feedbackBy(): MorphMany
    {
        return $this->morphMany(ComplaintFeedback::class, 'collectable');
    }

    /**
     * Relation with geo areas table via pivote user_ga
     */
    public function ca(): BelongsToMany
    {
        return $this->belongsToMany(Ca::class, 'adm_user_ca', 'user_id', 'ca_id');
    }

    /**
     * 
     * Payment colection relations
     * 
     */
    /**
     * Invoice payments
     */
    public function invoicePayments(): HasMany
    {
        return $this->hasMany(InvoicePayment::class, 'created_by');
    }

    /**
     * SD Payments
     */
    public function sdPayments(): HasMany
    {
        return $this->hasMany(ConsumerSdPayment::class, 'created_by');
    } 

    /**
     * Pivot Relation 
     */
    public function consumers() : BelongsToMany
    {
        return $this->belongsToMany(Consumer::class, 'adm_user_ca',
            'user_id',
            'ca_id',
            'id',
            'ca_id'
        );
    }
    /***
     * Pivot Relation
     */
    public function teams():BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'adm_team_users', 'user_id', 'team_id');
    }

    /**
     * Relation with Designation
     */
    public function designation():BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }
}
