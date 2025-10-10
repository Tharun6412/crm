<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'adm_district';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
        'ccavenue_name_meil',
        'ccavenue_name',
        'display_name',
        'code',
        'state',
        'geo_area',
        'status',
        'contact_no',
        'coordinates',
        'created_by',
    ];
}