<?php

namespace App\Models\Consumer;

use App\Models\Admin\User;
use App\Models\Master\MasterConsumerStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumerStatus extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cns_consumer_status';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'consumer_id',
        'lat',
        'lng',
        'status_id',
        'notes',
        'created_by',
    ];

    /**
     * Relation with Consumer
     */
    public function consumer():BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'consumer_id')->withDefault();
    }

    /**
     * Relation with Segments
     */
    public function status() :BelongsTo
    {
        return $this->belongsTo(MasterConsumerStatus::class, 'status_id')->withDefault();
    }

    /**
     * Realtion with CreatedBy
     */
    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
}