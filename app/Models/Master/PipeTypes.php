<?php
/**
 * Pipeline Types
 */
namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class PipeTypes extends Model
{
    protected $table = 'pms_pipes';

    protected $fillable = [
        'type',
        'name',
        'size',
    ];
}