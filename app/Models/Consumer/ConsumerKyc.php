<?php

namespace App\Models\Consumer;

use App\Models\Admin\User;
use App\Models\DocumentCentre\Documents;
use App\Models\DocumentCentre\DocumentTypes;
use App\Models\Master\ConsumerNomineeRelation;
use App\Models\Master\MasterConsumerStatus;
use App\Models\Master\Title;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumerKyc extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cns_consumer_kyc';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'consumer_id',
        'title',
        'fname',
        'lname',
        'cof',
        'cof_name',
        'aadhar',
        'phone',
        'phone_alt',
        'email',
        'nominee',
        'nominee_relation_id',
        'created_by',
    ];

    protected $appends = ['name'];

    protected function name(): Attribute
    {
        return Attribute::get(fn () => "{$this->fname} {$this->lname}");
    }

    /**
     * Relation with Consumer
     */
    public function consumer():BelongsTo
    {
        return $this->belongsTo(Consumer::class, 'consumer_id');
    }

    /**
     * Relation with Care OF
     */
    public function cofDisplay():BelongsTo
    {
        return $this->belongsTo(Title::class, 'cof');
    }
    /**
     * Relation with TitleDisplay
     */
    public function titleDisplay() : BelongsTo
    {
        return $this->belongsTo(Title::class, 'title');
    }

    /**
     * Relation with Nominee Relation
     */
    public function nomineeRelation():BelongsTo
    {
        return $this->belongsTo(ConsumerNomineeRelation::class, 'nominee_relation_id');
    }

    /**
     * Relation with Cretaed By
     */
    public function createdBy() :BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}