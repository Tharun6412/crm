<?php

namespace App\Models\Consumer;

use App\Models\Admin\Team;
use App\Models\Admin\User;
<<<<<<< Updated upstream
use App\Models\Master\MasterConsumerStatus;
=======
>>>>>>> Stashed changes
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamConsumer extends Model
{
<<<<<<< Updated upstream
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cns_consumer_teams';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
=======
    protected $table = 'cns_consumer_teams';
>>>>>>> Stashed changes
    protected $fillable = [
        'consumer_id',
        'team_id',
        'status_id',
        'status',
        'updated_by',
    ];

    /**
<<<<<<< Updated upstream
     * Relation with Consumer
     */
    public function consumer(): BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'consumer_id');
    }

    /**
     * Relation with Team
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    /**
     * Relation with status
     */
    public function status() : BelongsTo
    {
        return $this->belongsTo(MasterConsumerStatus::class, 'status_id');
    }
}
=======
     * Relation with Consumers
     */
    public function consumer(): BelongsTo
    {
        return $this->belongsTo(Consumer::class,'consumer_id');
    }
    /**
     * Relation with Teams
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class,'team_id');
    }
    /**
     * Relation with user
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class,'updated_by');
    }
}
>>>>>>> Stashed changes
