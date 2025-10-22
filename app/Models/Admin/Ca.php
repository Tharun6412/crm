<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ca extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'adm_ca';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'code',
        'name',
        'ga_id',
        'district_id',
        'status',
    ];
}