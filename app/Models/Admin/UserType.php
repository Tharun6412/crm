<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $table = 'adm_user_types';
    protected $fillable = [
        'name',
    ];
}
