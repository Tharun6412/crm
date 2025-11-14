<?php

namespace App\Models\Spot;

use App\Models\DocumentCentre\Documents;
use App\Models\Admin\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProspectDocuments extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'spt_prospect_documents';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'prospect_id',
        'document_type_id',
        'offer_count',
        'doc_file_id',
        'file_name',
        'path',
        'status',
        'win',
        'created_at',
        'created_by',
    ];

    /**
     * Timestamps False
     */
    public $timestamps= false;

    /**
     * Relation with Document Type
     */
    public function documentType():BelongsTo
    {
        return $this->belongsTo(DocumentTypes::class, 'document_type_id')->withDefault();
    }

    /**
     * Relation with File
     */
    public function file() : BelongsTo
    {
        return $this->belongsTo(Documents::class, 'doc_file_id')->withDefault();
    }

    /**
     * Relation with User
     */
    public function createdBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
}