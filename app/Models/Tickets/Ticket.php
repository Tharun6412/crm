<?php

namespace App\Models\Tickets;

use App\Models\Admin\User;
use App\Models\Consumer\Consumer;
use App\Models\Master\TicketCategory;
use App\Models\Master\TicketStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $table='tkt_tickets';
    protected $fillable = [
        'code',
        'consumer_id',
        'category_id',
        'status_id',
        'description',
        'created_by',
        'updated_by',
    ];
    /**
     * Relation with Consumer
     */
    public function consumer(): BelongsTo
    {
        return $this->belongsTo(Consumer::class,'consumer_id');
    }
    /**
     * Relation with Ticket Categories
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class,'category_id');
    }
    /**
     * Relation with Ticket Status
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class,'status_id');
    }
    /**
     * Relation with User
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class,'created_by');
    }
    /**
     * Relation with User
     */
    Public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class,'updated_by');
    }
    /**
     * Relation with statusHistory
     */
    public function statusHistory(): HasMany
    {
        return $this->hasMany(TicketStatusHistory::class,'ticket_id');
    }
}
