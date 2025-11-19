<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentType extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_pay_types';

    /**
     * The attributes that are mass assignable
     * @var array <int string>
     */
    protected $fillable = [
        'name',
        'status',
    ];
}