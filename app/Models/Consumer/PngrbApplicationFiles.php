<?php

namespace App\Models\Consumer;

use App\Models\DocumentCentre\Documents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PngrbApplicationFiles extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cns_pngrb_application_files';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [ 
        'application_id',
        'file_id',
        'type',
        'category',
        'created_at',
    ];

    /**
     * Disable updated_at from default function
     */
    const UPDATED_AT = null;

    /**
     * Enable timestamp to insert in created_at
     */
    public $timestamps = true;

    /**
     * Relation with PNGRB Applications
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(PngrbApplications::class, 'application_id'); 
    }

    /**
     * Relation with document center (files)
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(Documents::class, 'file_id');
    }
}