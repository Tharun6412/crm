<?php

namespace App\Models\Spot;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'spt_status';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
        'type',
        'parent_id',
        'icon',
        'color',
    ];

    /**
     * Parent Relation 
     */
    public function parent():BelongsTo
    {
        return $this->belongsTo(Status::class, 'parent_id')->withDefault();
    }

    /**
     * Child Relation
     */
    public function children():HasMany
    {
        return $this->hasMany(Status::class, 'parent_id');
    }
}