<?php

namespace App\Models\Consumer;

use App\Models\Admin\User;
use App\Models\Master\GeyserStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumerGeyserStatus extends Model
{
    /**
     * cns_geyser_status table
     */
    protected $table = 'cns_geyser_status';
    protected $fillable = [
        'geyser_id',
        'status_id',
        'notes',
        'created_by',
    ];
    /**
     * Relation with cns_geyser
     */
    public function geyser(): BelongsTo
    {
        return $this->belongsTo(ConsumerGeyser::class,'geyser_id');
    }
    /**
     * Relation with CreatedBy
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class,'created_by')->withDefault();
    }
    /**
     * Relation with status
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(GeyserStatus::class,'status_id');
    }
}
