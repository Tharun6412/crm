<?php

namespace App\Models\Lms;

use Illuminate\Database\Eloquent\Model;

class LeadChannel extends Model
{
    protected $table = 'lms_lead_channels';
    protected $fillable = [
        'name',
    ];
}
