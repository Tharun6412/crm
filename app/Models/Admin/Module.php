<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'adm_modules';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
        'slug',
        'url',
        'package_id',
        'parent_id',
        'icon',
        'position',
        'status',
        'created_by',
    ];

    /**
     * Parent-child relationship
     */
    public function parent()
    {
        return $this->belongsTo(Module::class, 'parent_id');
    }

    /**
     * 
     */
    public function children()
    {
        return $this->hasMany(Module::class, 'parent_id');
    }

    /**
     * Recursive all childs
     */
    public function recursiveChilds()
    {
        return $this->children()->with('recursiveChilds')->orderBy('position');
    }

    /**
     * Module Urls for the module
     */
    public function moduleUrls(): HasMany
    {
        return $this->hasMany(ModuleUrl::class);
    }

    /**
     * Module actions for the module
     */
    public function moduleActions(): HasMany
    {
        return $this->hasMany(ModuleAction::class);
    }

    /**
     * Recursive active childs
     */
    public function recursiveActiveChilds()
    {
        // return $this->children()->with('recursiveActiveChilds')->where('status', 1)->whereIn('id', session()->get('user')['modules']);
        $q = $this->children()->with('recursiveActiveChilds')->where('status', 1);
        // Get only allocated modules
        if(isAdmin() OR isSuperAdmin()){/* No action */} else {
            $q->whereIn('id', session()->get('user')['modules']);
        }
        return $q->orderBy('position');
    }
}