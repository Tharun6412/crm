<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Area extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'adm_areas';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
        'ca_id',
        'status',
    ];
}