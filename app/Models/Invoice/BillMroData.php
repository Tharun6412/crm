<?php
namespace App\Models\Invoice;

use App\Models\Consumer\Consumer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillMroData extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'bil_mro_data';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'consumer_id',
        'mro_number',
        'schedule_date',
        'mro_data',
        'invoice_id',
        'status_id',
        'mro_batch_id',
        'created_at',
        'updated_at'
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

    /**
     * Relation with the consumer table.
     */
    public function consumer():BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'consumer_id');
    }

    /**
     * Relation with mro batches table
     */
    public function mroBatch():BelongsTo
    {
        return $this->belongsTo(BillMroBatch::class,'mro_batch_id');
    }
}