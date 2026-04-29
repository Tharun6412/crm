<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
/**
 * complaint categories types modal 
 */
class ComplaintCategoryType extends Model
{
    protected $table = 'mst_cmp_category_types';

    protected $fillable = [
        'name',
    ];
}
