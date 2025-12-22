<?php

namespace App\Models\Master;

use App\Models\Consumer\Consumer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterConsumerStatus extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_cns_status';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Relation with consumers
     */
    public function consumers()
    {
        $this->hasMany(Consumer::class, 'status_id');
    }

    /**
     * Route matching
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}