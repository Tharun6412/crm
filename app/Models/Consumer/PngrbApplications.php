<?php

namespace App\Models\Consumer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PngrbApplications extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cns_pngrb_applications';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'applicationNumber',
        'cgdId',
        'gaId',
        'status',
        'ekycStatus',
        'serviceabilityStatus',
        // Applicant Info
        'name',
        'mobileNumber',
        'father_spouse',
        'dob',
        'email',
        'whatsapp',
        // PNG Address
        'houseNo',
        'floor',
        'society',
        'area',
        'city',
        'district',
        'state',
        'pincode',
        'premiseType',
        'occupancyType',
        'latitude',
        'longitude',
        'response_code',
        'response_message',
        'consumer_id',
    ];

    /**
     * Relation witn consumer
     */
    public function consumer(): BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'consumer_id');
    }
}