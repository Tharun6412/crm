<?php

namespace App\Models\Lms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeadStatus extends Model
{
    protected $table = 'lms_lead_status';
    protected $fillable = [
        'name',
        'parent_id',
    ];
    /**
     * Relation with Parent Status
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(LeadStatus::class, 'parent_id');
    }
    /**
     * Relation With Child Status
     */
    public function children(): HasMany
    {
        return $this->hasMany(LeadStatus::class, 'parent_id');
    }

}
