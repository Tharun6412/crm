<?php

namespace App\Models\Tickets;

use App\Models\Admin\User;
use App\Models\Master\TicketStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketStatusHistory extends Model
{
    protected $table = "tkt_status";
    protected $fillable = [
        'ticket_id',
        'status_id',
        'notes',
        'updated_by',
    ];
    /**
     * Relation with Tickets
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class,'ticket_id');
    }
    /**
     * Relation with Status
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class,'status_id');
    }
    /**
     * Relation with user
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class,'updated_by');
    }
}
