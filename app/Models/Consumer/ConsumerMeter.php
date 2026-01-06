<?php

namespace App\Models\Consumer;

use App\Models\Admin\User;
use App\Models\DocumentCentre\Documents;
use App\Models\Invoice\BillInvoiceConsumption;
use App\Models\Master\ConsumerMeterStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'file_id',
        'meter_no',
        'meter_serial_no',
        'initial_reading',
        'install_date',
        'install_by',
        'status',
        'created_by',
        'updated_by'
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
     * Realtion with UpdatedBy
     */
    public function updatedBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }

    /**
     * Relation with File
     */
    public function file():BelongsTo
    {
        return $this->belongsTo(Documents::class, 'file_id')->withDefault();
    }

    /**
     * Realtion with Status
     */
    public function meterStatus():BelongsTo
    {
        return $this->belongsTo(ConsumerMeterStatus::class, 'status')->withDefault();
    }

    /**
     * Relation with Meter Consumption Latest
     */
    public function meterConsumption():HasOne
    {
        return $this->hasOne(BillInvoiceConsumption::class, 'meter_id', 'id')->latestOfMany('id');
    }
}