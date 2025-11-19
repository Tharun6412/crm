<?php

namespace App\Models\Consumer;

use App\Models\Admin\User;
use App\Models\Master\ConsumerMeterStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumerMeter extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cns_consumer_meters';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'consumer_id',
        'meter_no',
        'meter_serial_no',
        'initial_reading',
        'install_date',
        'install_by',
        'status',
        'created_by',
    ];

    /**
     * casts
     */
    public function casts() {
        return [
            'install_date' => 'datetime',
        ];
    }
    /**
     * Relation with Consumer
     */
    public function consumer():BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'consumer_id')->withDefault();
    }

    /**
     * Realtion with InstallBy
     */
    public function installBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'install_by')->withDefault();
    }
    /**
     * Realtion with CreatedBy
     */
    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    /**
     * Realtion with Status
     */
    public function meterStatus():BelongsTo
    {
        return $this->belongsTo(ConsumerMeterStatus::class, 'status')->withDefault();
    }
}