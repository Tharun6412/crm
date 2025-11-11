<?php
namespace App\Models\Spot;

use App\Models\Master\Ga;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Target extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'spot_targets';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'ga_id',
        'target_date',
        'segment_id',
        'target_quantity',
        'target_value',
        'created_by'
    ];

    // public function casts()
    // {
    //     return [
    //         'target_date' => 'date',
    //     ];
    // }

    /**
     * Relation with Ga
     */
    public function ga():BelongsTo
    {
        return $this->belongsTo(Ga::class, 'ga_id')->withDefault();
    }
}