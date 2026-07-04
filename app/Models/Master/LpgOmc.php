<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class LpgOmc extends Model
{
    protected $table = 'mst_lpg_omcs';
    protected $fillable = [
        'name',
    ];
}
