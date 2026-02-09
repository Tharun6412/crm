<?php
namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class MasterMroStatus extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_mro_status';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
    ];
}