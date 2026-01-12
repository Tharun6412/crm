<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OTP extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'op_otps';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'identifier',
        'purpose',
        'module',
        'otp',
        'expires_at',
        'is_used',
    ];
}