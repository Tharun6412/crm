<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class TransactionSource extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_pay_transaction_sources';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
    ];
}