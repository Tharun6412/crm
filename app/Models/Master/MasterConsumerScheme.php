<?php

namespace App\Models\Master;

use App\Models\Admin\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MasterConsumerScheme extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_cns_schemes';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'segment_id',
        'code',
        'name',
        'registration',
        'security',
        'consumption',
        'total_deposit',
        'min_payment',
        'emi_amount',
        'rental_amount',
        'status',
        'scheme_payment_id',
        'connection_type_id',
        'bonus',
        'created_by',
    ];

    /**
     * Relation with Segment
     */
    public function segment():BelongsTo
    {
        return $this->belongsTo(Segment::class, 'segment_id');
    }

    /**
     * Relation with COnnection types
     */
    public function connectionType(): BelongsTo
    {
        return $this->belongsTo(ConnectionType::class);
    }

    /**
     * Relation with Scheme Payments
     */
    public function schemePayment():BelongsTo
    {
        return $this->belongsTo(ConsumerSchemePayment::class, 'scheme_payment_id');
    }

    /**
     * Has many relation with consumer schemes GA
     */
    public function schemesGa(): HasMany
    {
        return $this->hasMany(ConsumerSchemeGa::class, 'scheme_id');
    }

    /**
     * 
     * Belongs to Many relation with GA
     */
    public function gas(): BelongsToMany
    {
        return $this->belongsToMany(Ga::class, 'mst_cns_scheme_ga', 'scheme_id', 'ga_id');
    }

    /**
     * Relation with Users 
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class,'created_by');
    }
}