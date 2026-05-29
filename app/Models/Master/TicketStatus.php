<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model
{
    protected $table="mst_tkt_status";
    protected $fillable = [
        'name',
    ];
}
