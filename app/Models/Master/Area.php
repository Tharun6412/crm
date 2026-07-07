<?php

namespace App\Models\Master;

use App\Models\Admin\User;
use App\Models\Lms\DeliveryUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Area extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_areas';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
        'ca_id',
        'status',
    ];

    /**
     * Relation with CA
     */
    public function ca():BelongsTo
    {
        return $this->belongsTo(Ca::class, 'ca_id')->withDefault();
    }

    /**
     * Relation with Pivot Table ad_user_area
     */
    public function user() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'adm_user_areas', 'area_id', 'user_id');
    }

    /**
     * Relation with Area
     */
    public function duAreas(): BelongsToMany
    {
        return $this->belongsToMany(DeliveryUnit::class,'lms_du_areas','area_id','du_id');
    }

}