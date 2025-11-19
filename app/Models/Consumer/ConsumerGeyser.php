<?php

namespace App\Models\Consumer;

use App\Models\Admin\User;
use App\Models\Invoice\BillInvoice;
use App\Models\Master\ConsumerGeyserStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumerGeyser extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cns_geysers';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'consumer_id',
        'code',
        'amount',
        'invoice_id',
        'status_id',
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
     * Relation with Invoice
     */
    public function invoice():BelongsTo
    {
        return $this->belongsTo(BillInvoice::class, 'invoice_id')->withDefault();
    }

    /**
     * Relation with status
     */
    public function status():BelongsTo
    {
        return $this->belongsTo(ConsumerGeyserStatus::class, 'status_id')->withDefault();
    }

    /**
     * Realtion with CreatedBy
     */
    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

}