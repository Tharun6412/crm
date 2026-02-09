<?php
namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;

class BillMroDataHistory extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'bil_mro_data_history';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'mro_data_id',
        'status_id',
        'notes',
    ];
}