<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;



class ConsumerScheme extends Model
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
        'created_by',
    ];

    /**
     * Relation with Segment
     */
    public function segment():BelongsTo
    {
        return $this->belongsTo(Segment::class, 'segment_id')->withDefault();
    }

    /**
     * Relation with Scheme Payments
     */
    public function schemePayment():BelongsTo
    {
        return $this->belongsTo(ConsumerSchemePayment::class, 'scheme_payment_id')->withDefault();
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