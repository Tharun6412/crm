<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class ComplaintIrregularity extends Model
{
    protected $table = 'mst_cmp_irregularities';
    protected $fillable = [
        'name',
    ];
}
