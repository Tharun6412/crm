<?php

namespace App\Models\Admin;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Complaint\ComplaintComment;
use App\Models\Complaint\ComplaintFeedback;
use App\Models\Consumer\ConsumerSdPayment;
use App\Models\Invoice\InvoicePayment;
use App\Models\Master\Department;
use App\Models\Master\Ga;
use App\Models\Spot\SpotRoles;
use App\Models\Traits\HasRoles;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserExport extends Model
{
    protected $table = 'adm_user_exports';
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'file_name',
        'status',
    ];

    /**
     * Relation with User
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
