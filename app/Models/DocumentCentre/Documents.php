<?php

namespace App\Models\DocumentCentre;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documents extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'dc_files';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'doc_number',
        'disk',
        'dc_type_id',
        'file_name_original',
        'file_path',
        'url',
        'tag',
        'description',
        'status',
        'created_by',
    ];

    /**
     * Relation with document types
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(DocumentTypes::class, 'dc_type_id');
    }

    /**
     * Relation with users
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}