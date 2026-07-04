<?php

namespace App\Models\Consumer;

use App\Models\Admin\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerifyConsumer extends Model
{
    protected $table = 'vfy_consumers';
    protected $fillable = [
        'consumer_id',
        'status',
        'remarks',
        'updated_remarks',
        'created_by',
        'updated_by',
    ];
    /**
     * Relation with consumers
     */
    public function consumer(): BelongsTo
    {
        return $this->belongsTo(Consumer::class,'consumer_id','id');
    }
    /**
     * Relation with user
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class,'created_by');
    }
    /**
     * Relation with user
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class,'updated_by');
    }
    /**
     * Relation with Consumer Steps
     */
    public function steps()
    {
        return $this->hasMany(VerifyConsumerStep::class, 'verify_consumer_id');
    }
}
