<?php

namespace App\Models\Consumer;

use App\Models\Admin\User;
use App\Models\Invoice\BillInvoice;
use App\Models\Master\ConsumerGeyserStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumerMeterChanges extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cns_meter_changes';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'consumer_id',
        'meter_id',
        'prev_reading',
        'end_reading',
        'consumption',
        'new_meter_id',
        'request_date',
        'replace_date',
        'reason',
        'status_id',
        'remarks',
        'technician_id',
        'created_by',
    ];

    /**
     * Casts
     */
    public function casts() {
        return [
            'request_date' => 'date',
            'replace_date' => 'date',
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
     * Relation with Meter
     */
    public function meter():BelongsTo
    {
        return $this->belongsTo(ConsumerMeter::class, 'meter_id')->withDefault();
    }

    /**
     * Relation with New Meter
     */
    public function newMeter():BelongsTo
    {
        return $this->belongsTo(ConsumerMeter::class, 'new_meter_id')->withDefault();
    }

    /**
     * Realtion with CreatedBy
     */
    public function technician():BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id')->withDefault();
    }

    /**
     * Realtion with CreatedBy
     */
    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

}