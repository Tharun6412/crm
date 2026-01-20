<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentGateway extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_payment_gateways';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'gateway',
        'mode',
        'credentials',
        'is_active',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'credentials' => 'encrypted:array',
    ];

    /**
     * Relation with payment gateway details
     */
    public function details(): HasMany
    {
        return $this->hasMany(PaymentGatewayDetails::class);
    }
}