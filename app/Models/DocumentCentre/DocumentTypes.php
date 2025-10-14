<?php

namespace App\Models\DocumentCentre;

use Illuminate\Database\Eloquent\Model;

class DocumentTypes extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'dc_types';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
    ];
}