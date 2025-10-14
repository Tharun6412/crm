<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleUrl extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'adm_module_urls';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
        'module_id',
        'url',
    ];

    /**
     * Module relationship
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}