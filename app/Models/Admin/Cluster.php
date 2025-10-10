<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'adm_clusters';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
    ];
}