<?php

namespace App\Models\Master;

use App\Models\Admin\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketCategory extends Model
{
    protected $table="mst_tkt_categories";
    protected $fillable = [
        'name',
        'department_id',
        'created_by',
        'updated_by',
    ];
    /**
     * Relation with departments
     */
    public function departments(): BelongsTo
    {
        return $this->belongsTo(Department::class,'department_id');
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
}
