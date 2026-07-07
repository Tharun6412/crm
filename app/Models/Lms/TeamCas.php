<?php

namespace App\Models\Lms;

use App\Models\Master\Ca;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamCas extends Model
{
    protected $table = 'lms_team_cas';
    protected $fillable = [
        'team_id',
        'ca_id',
    ];
    /**
     * Relation with team
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class,'team_id');
    }
    /**
     * Relation with ca
     */
    public function ca(): BelongsTo
    {
        return $this->belongsTo(Ca::class, 'ca_id');
    }
}

