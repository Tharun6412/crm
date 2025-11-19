<?php

namespace App\Models\Consumer;

use App\Models\Admin\User;
use App\Models\Master\RefundStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumerRefundStatus extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'ref_refund_status';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'refund_id',
        'status_id',
        'notes',
        'created_by',
    ];

    /**
     * Relation with Consumer
     */
    public function refund():BelongsTo
    {
        return $this->belongsTo(ConsumerRefund::class, 'refund_id')->withDefault();
    }

    /**
     * Relation with Status
     */
    public function status():BelongsTo
    {
        return $this->belongsTo(RefundStatus::class, 'status_id')->withDefault();
    }

    /**
     * Relation with CreatedBy
     */
    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
}