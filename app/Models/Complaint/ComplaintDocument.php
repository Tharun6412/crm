<?php

namespace App\Models\Complaint;

use App\Models\Admin\User;
use App\Models\DocumentCentre\Documents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplaintDocument extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cmp_complaint_documents';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'complaint_id',
        'file_id',
    ];

    /**
     * Relation with Complaint
     */
    public function complaint() :BelongsTo
    {
        return $this->belongsTo(Complaint::class, 'complaint_id')->withDefault();
    }

    /**
     * Realtion with File
     */
    public function file():BelongsTo
    {
        return $this->belongsTo(Documents::class, 'file_id')->withDefault();
    }
}