<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'api_keys';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
        'key',
        'key_original',
        'is_active',
        'expires_at',
        'rate_limit'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'is_active'  => 'boolean',
    ];
}