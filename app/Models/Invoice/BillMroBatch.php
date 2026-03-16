<?php 
namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;

class BillMroBatch extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'bil_mro_batches';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'batch_id',
        'schedule_date',
    ];
    /**
     * Casts Dates
     */
    public function casts() {
        return [
            'schedule_date' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime'
        ];
    }
}