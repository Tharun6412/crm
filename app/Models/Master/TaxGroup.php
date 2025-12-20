<?php

namespace App\Models\Master;

use App\Models\Invoice\BillStateInvoiceCounter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxGroup extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'mst_tax_groups';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Relations with tax
     */
    public function taxes(): HasMany
    {
        return $this->hasMany(Tax::class);
    }

    /**
     * Relations with counter
     */
    public function counter(): HasMany
    {
        return $this->hasMany(BillStateInvoiceCounter::class);
    }
}