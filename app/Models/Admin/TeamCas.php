<?php

namespace App\Models\Admin;

use App\Models\Master\Ca;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamCas extends Model
{
    protected $table = 'adm_team_cas';
    protected $fillable = [
        'team_id',
        'ca_id',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class,'team_id');
    }

    public function ca(): BelongsTo
    {
        return $this->belongsTo(Ca::class, 'ca_id');
    }
}

