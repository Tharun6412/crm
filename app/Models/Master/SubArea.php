<?php

namespace App\Models\Master;

use App\Models\Admin\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubArea extends Model
{
    protected $table = "mst_sub_areas";
    protected $fillable = [
        'name',
        'area_id',
    ];
    /**
     * Relation with Area
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class,'area_id');
    }
}
