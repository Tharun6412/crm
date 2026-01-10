<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class PaymentGatewayDetails extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_payment_gateway_details';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'district_id',
        'payment_gateway_id',
        'sub_merchant_id',
    ];

}