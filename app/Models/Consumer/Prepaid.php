<?php

namespace App\Models\Consumer;

use App\Models\Admin\User;
use App\Models\Master\MasterConsumerScheme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prepaid extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cns_prepaid';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'consumer_id',
        'post_scheme_id',
        'pre_scheme_id',
        'conversion_date',
        'bonus',
        'bonus_status',
        'bonus_date',
        'balance',
        'balance_date',
        'hes_status',
        'hes_date',
        'commission_date',
        'commission_status',
        'bill_date',
        'bill_qty',
        'bill_amount',
        'bill_status',
        'notes',
        'created_by',
    ];

    // Casting
    protected $casts = [
        'conversion_date' => 'date',
        'bonus_date' => 'date',
        'balance_date' => 'date',
        'hes_date' => 'date',
    ];

    /**
     * Relation with consumers
     */
    public function consumers(): BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'consumer_id');
    }

    /**
     * Relation with scheme
     */
    public function postpaidScheme(): BelongsTo
    {
        return $this->belongsTo(MasterConsumerScheme::class, 'post_scheme_id');
    }

    /**
     * Relation with scheme
     */
    public function prepaidScheme(): BelongsTo
    {
        return $this->belongsTo(MasterConsumerScheme::class, 'pre_scheme_id');
    }

    /**
     * Relatio with User
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}