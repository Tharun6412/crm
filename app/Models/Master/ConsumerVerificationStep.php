<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class ConsumerVerificationStep extends Model
{
    protected $table = 'mst_verify_steps';
    protected $fillable = [
        'name'
    ];
}
