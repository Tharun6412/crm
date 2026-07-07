<?php

namespace App\Models\Lms;

use App\Models\Admin\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class TeamUser extends Model
{
    protected $table = 'lms_team_users';
    protected $fillable = [
        'team_id',
        'user_id',
    ];
    /**
     * Relation with team
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class,'team_id');
    }
    /**
     * Relation with user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
}
