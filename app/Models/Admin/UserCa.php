<?php

namespace App\Models\Admin;

use App\Models\Master\Ca;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCa extends Model
{
    protected $table = 'adm_user_ca';
    protected $fillable = [
        'user_id',
        'ca_id',
    ];
    /**
     * Relation with user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
    /**
     * Relation with ca
     */
    public function ca(): BelongsTo
    {
        return $this->belongsTo(Ca::class,'ca_id');
    }
}
