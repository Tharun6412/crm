<?php

namespace App\Models\Consumer;

use App\Models\Admin\User;
use App\Models\Complaint\ComplaintComment;
use App\Models\Complaint\ComplaintFeedback;
use App\Models\Invoice\BillInvoice;
use App\Models\Master\Area;
use App\Models\Master\Ca;
use App\Models\Master\ConsumerGasRequired;
use App\Models\Master\ConsumerNomineeRelation;
use App\Models\Master\District;
use App\Models\Master\FirmType;
use App\Models\Master\FuelType;
use App\Models\Master\Ga;
use App\Models\Master\MasterConsumerStatus;
use App\Models\Master\PaymentType;
use App\Models\Master\Segment;
use App\Models\Master\State;
use App\Models\Master\Title;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Consumer extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     */
    protected $table = 'cns_consumers';

    /**
     * The attributes that are mass assignable
     * 
     * @var array <int string>
     */
    protected $fillable = [
        'segment_id',
        't_crn',
        'crn',
        'title',
        'fname',
        'lname',
        'cof',
        'cof_name',
        'aadhar',
        'pan',
        'gst',
        'email',
        'phone',
        'phone_alt',
        'nominee',
        'nominee_relation_id',
        'hno',
        'street',
        'colony',
        'city',
        'ward',
        'area_id',
        'ca_id',
        'district_id',
        'ga_id',
        'state_id',
        'pincode',
        'lpg_connections',
        'dcq',
        'expected_date',
        'distance',
        'property_type',
        'owner_name',
        'owner_phone',
        'tenant_name',
        'tenant_phone',
        'tenant_email',
        'gas_required_id',
        'firm_type_id',
        'fuel_id',
        'fuel_qty',
        'peak_qty',
        'hours',
        'req_pressure',
        'req_flow',
        'payment_id',
        'status_id',
        'created_by',
        'updated_by',
    ];
    /**
     * Casts Dates
     */
    public function casts() {
        return [
            'expected_date' => 'date',
        ];
    }

    protected $appends = ['name'];

    protected function name(): Attribute
    {
        return Attribute::get(fn () => "{$this->fname} {$this->lname}");
    }

    /**
     * Relation with TitleDisplay
     */
    public function titleDisplay() : BelongsTo
    {
        return $this->belongsTo(Title::class, 'title')->withDefault();
    }

    /**
     * Relation with Segments
     */
    public function segment() :BelongsTo
    {
        return $this->belongsTo(Segment::class, 'segment_id')->withDefault();
    }

    /**
     * Relation with Firm Type
     */
    public function firmType():BelongsTo
    {
        return $this->belongsTo(FirmType::class, 'firm_type_id')->withDefault();
    }

    /**
     * Relation with Fuel Type
     */
    public function fuelType():BelongsTo
    {
        return $this->belongsTo(FuelType::class, 'fuel_id')->withDefault();
    }

    /**
     * Relation with Gas Required
     */
    public function gasRequired():BelongsTo
    {
        return $this->belongsTo(ConsumerGasRequired::class, 'gas_required_id')->withDefault();
    }

    /**
     * Relation with Nominee Relation
     */
    public function nomineeRelation():BelongsTo
    {
        return $this->belongsTo(ConsumerNomineeRelation::class, 'nominee_relation_id')->withDefault();
    }

    /**
     * Relation with Payment
     */
    public function payment():BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'payment_id')->withDefault();
    }

    /**
     * Relation with District
     */
    public function district():BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id')->withDefault();
    }

    /**
     * Relation with GA
     */
    public function ga():BelongsTo
    {
        return $this->belongsTo(Ga::class, 'ga_id')->withDefault();
    }

    /**
     * Relation with State
     */
    public function state():BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id')->withDefault();
    }

    /**
     * Relation with Invoice
     */
    public function area():BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id')->withDefault();
    }

    /**
     * Realtion with CreatedBy
     */
    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    /**
     * Realtion with UpdatedBy
     */
    public function updatedBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }

    /**
     * Relation with Price History
     */
    public function ca() :BelongsTo
    {
        return $this->belongsTo(Ca::class, 'ca_id')->withDefault();
    }

    /**
     * Relation with Status
     */
    public function status() :BelongsTo
    {
        return $this->belongsTo(MasterConsumerStatus::class, 'status_id')->withDefault();
    }
    
    /**
     * Relation with Meter
     */
    public function meter(): HasOne
    {
        return $this->hasOne(ConsumerMeter::class, 'consumer_id', 'id');
    }

    /**
     * Relation with Consumer Status History
     */
    public function statusHistory():HasMany
    {
        return $this->hasMany(ConsumerStatus::class, 'consumer_id', 'id')->orderBy('created_at', 'desc');
    }

    /**
     * Relation with scheme
     */
    public function scheme(): HasOne
    {
        return $this->hasOne(ConsumerScheme::class, 'consumer_id', 'id');
    }

    /**
     * Relation with SDPaymentHistory
     */
    public function sdPayment() : HasMany
    {
        return $this->hasMany(ConsumerSdPayment::class, 'consumer_id', 'id')->orderBy('created_at', 'desc');
    }

    /**
     * #PolyMorphic relation
     * Relation with complaint comments
     * MorphMany
     */
    public function commentsBy():MorphMany
    {
        return $this->morphMany(ComplaintComment::class, 'commentable');
    }

    /**
     * #PolyMorphic relation
     * Relation with complaint Feedback
     * MorphMany
     */
    public function feedbackBy():MorphMany
    {
        return $this->morphMany(ComplaintFeedback::class, 'collectable');
    }

    /**
     * Invoices
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(BillInvoice::class);
    }
}