<?php

namespace App\Models\Master;

use App\Models\Admin\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComplaintCategory extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_cmp_categories';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
        'resolution',
        'resolution_type',
        'type_id',
        'priority_id',
        'department_id',
        'parent_id',
        'tag_id',
        'position',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Parent relation
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ComplaintCategory::class, 'parent_id');
    }

    /**
     * Child relation
     */
    public function children(): HasMany
    {
        return $this->hasMany(ComplaintCategory::class, 'parent_id')->orderBy('position');
    }

    /**
     * Relation with type
     */
    /**public function type(): BelongsTo
    {
        return $this->belongsTo(ComplaintType::class);
    } **/
    public function type(): BelongsTo
    {
        return $this->belongsTo(ComplaintCategoryType::class);
    }
    /**
     * Relation with Priority
     */

    public function priority(): BelongsTo
    {
        return $this->belongsTo(ComplaintPriority::class,'priority_id');
    }

    /**
     * Relation with Department
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Relation with User
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relation with User
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    /**
     * Relation with Tag
     */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(ComplaintTag::class, 'tag_id');
    }
}