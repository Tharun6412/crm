<?php 
namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class ComplaintTag extends Model
{
    
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_cmp_tags';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
    ];
}