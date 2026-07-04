<?php

namespace App\Models\Consumer;

use App\Models\Master\ConsumerVerificationStep;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VerifyConsumerStep extends Model
{
    public $timestamps = false;
    protected $table = 'vfy_consumer_steps';
    protected $fillable = [
        'verify_consumer_id',
        'verify_step_id',
        'status',
        'remarks',
    ];
    /**
     * Relation with master steps
     */
    public function step():BelongsTo
    {
        return $this->belongsTo(ConsumerVerificationStep::class,'verify_step_id');
    }
    /**
     * Relation with verifyConsumer
     */
    public function verification()
    {
        return $this->belongsTo(VerifyConsumer::class, 'verify_consumer_id');
    }
}
