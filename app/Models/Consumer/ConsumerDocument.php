<?php

namespace App\Models\Consumer;

use App\Models\Admin\User;
use App\Models\DocumentCentre\Documents;
use App\Models\DocumentCentre\DocumentTypes;
use App\Models\Master\MasterConsumerStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumerDocument extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cns_consumer_documents';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'consumer_id',
        'status_id',
        'doc_type_id',
        'file_id',
        'notes',
    ];

    /**
     * Relation with Consumer
     */
    public function consumer():BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'consumer_id')->withDefault();
    }

    /**
     * Relation with File
     */
    public function file():BelongsTo
    {
        return $this->belongsTo(Documents::class, 'file_id');
    }

    /**
     * Relation with Document Type
     */
    public function docType():BelongsTo
    {
        return $this->belongsTo(DocumentTypes::class, 'doc_type_id');
    }

    /**
     * Relation with Status
     */
    public function status() :BelongsTo
    {
        return $this->belongsTo(MasterConsumerStatus::class, 'status_id');
    }
}