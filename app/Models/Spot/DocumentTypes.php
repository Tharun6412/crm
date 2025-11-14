<?php

namespace App\Models\Spot;

use Illuminate\Database\Eloquent\Model;

class DocumentTypes extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'spt_document_types';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
        'created_at',
        'created_by',
    ];

    /**
     * Timestamps False
     */
    public $timestamps= false;
}