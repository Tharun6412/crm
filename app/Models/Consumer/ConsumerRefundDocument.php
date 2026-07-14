<?php

namespace App\Models\Consumer;

use App\Models\Admin\User;
use App\Models\DocumentCentre\Documents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumerRefundDocument extends Model
{
    protected $table = 'ref_refund_documents';
    protected $fillable = [
        'request_id',
        'file_id',
        'notes',
        'created_by',
        'updated_by',
    ];
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
     * Relation with documents
     */
    public function file(): BelongsTo
        {
            return $this->belongsTo(Documents::class, 'file_id', 'id');
        }
}
