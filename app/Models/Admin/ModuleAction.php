<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleAction extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'adm_module_actions';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'module_id',
        'action',
        'slug',
    ];

    /**
     * Module relationship
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}