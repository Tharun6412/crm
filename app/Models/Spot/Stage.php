<?php

namespace App\Models\Spot;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stage extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'spt_stages';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
        'type',
        'parent_id',
    ];

    /**
     * Parent Relation 
     */
    public function parent():BelongsTo
    {
        return $this->belongsTo(Stage::class, 'parent_id')->withDefault();
    }

    /**
     * Child Relation
     */
    public function children():HasMany
    {
        return $this->hasMany(Stage::class, 'parent_id');
    }
}