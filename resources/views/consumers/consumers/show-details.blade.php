{{-- Show consumer details, tab content --}}

<div class="border rounded-top">
    <div class="bg-primary-subtle p-2 fs-5 fw-semibold">
        <i class="bi bi-person"></i>&nbsp;Consumer Details
    </div>
    <div class="p-2">
        <div class="row">
            <div class="col-md-6">
                <h4 class="text-primary fw-semibold">Details</h4>
                <dl class="row">
                    <dt class="col-sm-3">Segment</dt>
                    <dd class="col-sm-9">{{ $consumer->segment->name }}</dd>
                    <dt class="col-sm-3">CRN</dt>
                    <dd class="col-sm-9">{{ $consumer->crn }}</dd>
                    <dt class="col-sm-3">Name</dt>
                    <dd class="col-sm-9">{{ $consumer->name }}</dd>
                    <dt class="col-sm-3">Email</dt>
                    <dd class="col-sm-9">{{ $consumer->email }}</dd>
                    <dt class="col-sm-3">Aadhar</dt>
                    <dd class="col-sm-9">{{ maskNumber($consumer->aadhar) }}</dd>
                    <dt class="col-sm-3">Mobile</dt>
                    <dd class="col-sm-9">{{ maskNumber($consumer->phone) }}</dd>
                    <dt class="col-sm-3">Alternate Mobile</dt>
                    <dd class="col-sm-9">{{ maskNumber($consumer->phone_alt) }}</dd>
                    <dt class="col-sm-3">Status</dt>
                    <dd class="col-sm-9"><x-consumer.status :status="$consumer->status" /></dd>
                </dl>
                <h4 class="text-primary mt-3 fw-semibold">Scheme Details</h4>
                <dl class="row">
                    <dt class="col-sm-3">Scheme Name</dt>
                    <dd class="col-sm-9">{{ $consumer->scheme?->scheme?->name }}</dd>
                    <dt class="col-sm-3">Registration</dt>
                    <dd class="col-sm-9">{{ numberFormat($consumer->scheme?->scheme?->registration) }}</dd>
                    <dt class="col-sm-3">Security Deposit</dt>
                    <dd class="col-sm-9">{{ numberFormat($consumer->scheme?->security_deposit) }}</dd>
                    <dt class="col-sm-3">Consumption Deposit</dt>
                    <dd class="col-sm-9">{{ numberFormat($consumer->scheme?->consumption_deposit) }}</dd>
                </dl>
                <h4 class="text-primary mt-3 fw-semibold">Nominee Details</h4>
                <dl class="row">
                    <dt class="col-sm-3">Nominee Name</dt>
                    <dd class="col-sm-9">{{ $consumer->nominee }}</dd>
                    <dt class="col-sm-3">Nominee Relation</dt>
                    <dd class="col-sm-9">{{ $consumer->nomineeRelation->name }}</dd>
                </dl>
                <h4 class="text-primary mt-3 fw-semibold">Owner Details</h4>
                <dl class="row">
                    <dt class="col-sm-3">Property Type</dt>
                    <dd class="col-sm-9">
                        @switch($consumer->property_type)
                            @case(1)
                                {{ "Own" }}
                                @break
                            @case(2)
                                {{ "Rent" }}
                                @break
                            @case(3)
                                {{ "Lease" }}
                                @break
                            @default
                        @endswitch
                    </dd>
                    <dt class="col-sm-3">Owner Name</dt>
                    <dd class="col-sm-9">{{ $consumer->owner_name }}</dd>
                    <dt class="col-sm-3">Owner Phone</dt>
                    <dd class="col-sm-9">{{ maskNumber($consumer->owner_phone) }}</dd>
                </dl>
                @if ($consumer->segment_id == \App\Enums\SegmentType::DOMESTIC->value)
                    <h4 class="text-primary mt-3 fw-semibold">Tenant Details</h4>
                    <dl class="row">
                        <dt class="col-sm-3">Tenant Name</dt>
                        <dd class="col-sm-9">{{ $consumer->tenant_name }}</dd>
                        <dt class="col-sm-3">Tenant Phone</dt>
                        <dd class="col-sm-9">{{ maskNumber($consumer->tenant_phone) }}</dd>
                        <dt class="col-sm-3">Tenant Email</dt>
                        <dd class="col-sm-9">{{ $consumer->tenant_email }}</dd>
                    </dl>
                @endif
            </div>
            <div class="col-md-6">
                <h4 class="text-primary fw-semibold">Location</h4>
                <dl class="row">
                    <dt class="col-sm-3">Geo Area</dt>
                    <dd class="col-sm-9">{{ $consumer->ga->name }}</dd>
                    <dt class="col-sm-3">District</dt>
                    <dd class="col-sm-9">{{ $consumer->district->name }}</dd>
                    <dt class="col-sm-3">Charge Area</dt>
                    <dd class="col-sm-9">{{ $consumer->ca->name }}</dd>
                    <dt class="col-sm-3">Location</dt>
                    <dd class="col-sm-9">{{ $consumer->area->name }}</dd>
                </dl>
                <h4 class="text-primary mt-3 fw-semibold">Address</h4>
                <address>
                    <strong>{{ $consumer->name}}</strong><br>
                    {{ $consumer->cofDisplay?->name }} {{ $consumer->cof_name }}<br>
                    {{ $consumer->hno }}, {{ $consumer->street }},<br>
                    {{ $consumer->colony }}, {{ $consumer->city }},<br>
                    {{ $consumer->district->name ?? '' }}, {{ $consumer->ga->state->name ?? '' }} - {{ $consumer->pincode }}.
                </address>

                <h4 class="text-primary mt-3 fw-semibold">Meter Details</h4>
                <dl class="row">
                    <dt class="col-sm-3">Meter Number</dt>
                    <dd class="col-sm-9">{{ $consumer_meter?->meter_no }}</dd>
                    <dt class="col-sm-3">Serial Number</dt>
                    <dd class="col-sm-9">{{ $consumer_meter?->meter_serial_no }}</dd>
                    <dt class="col-sm-3">Initial Reading</dt>
                    <dd class="col-sm-9">{{ $consumer_meter?->initial_reading }}</dd>
                    <dt class="col-sm-3">Installation Date</dt>
                    <dd class="col-sm-9">{{ $consumer_meter?->install_date?->format('d-m-Y') }}</dd>
                    <dt class="col-sm-3">Installed By</dt>
                    <dd class="col-sm-9">{{ $consumer_meter?->installBy?->first_name }}&nbsp;{{ $consumer_meter?->installBy?->last_name }}</dd>
                </dl>
                <h4 class="text-primary mt-3 fw-semibold">Additional Details</h4>
                <dl class="row">
                    <dt class="col-sm-3">LPG Connections</dt>
                    <dd class="col-sm-9">{{ $consumer->lpg_connections }}</dd>
                    <dt class="col-sm-3">DCQ</dt>
                    <dd class="col-sm-9">{{ numberFormat($consumer->dcq, 2) }}</dd>
                    <dt class="col-sm-3">Expected Date</dt>
                    <dd class="col-sm-9">{{ $consumer->expected_date?->format('d-m-Y') }}</dd>
                    <dt class="col-sm-3">Distance&nbsp;(Mts)</dt>
                    <dd class="col-sm-9">{{ numberFormat($consumer->distance, 2) }}</dd>
                    <dt class="col-sm-3">Natural Gas For</dt>
                    <dd class="col-sm-9">{{ $consumer->gasRequired?->name }}</dd>
                </dl>
            </div>
        </div>
        <h4 class="text-primary fw-semibold">Status History</h4>
        <table class="table table-bordered table-primary table-hover">
            <thead class="table-primary">
                <tr>
                    <th width="1%">S.No</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th>Created Date</th>
                    <th>Created By</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($consumer->statusHistory as $history)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><x-consumer.status :status="$history->status" /></td>
                        <td>{{ $history->notes }}</td>
                        <td>{{ $history->created_at?->format('d-m-Y H:i:s') }}</td>
                        <td>{{ $history->createdBy?->first_name }}&nbsp;{{ $history->createdBy?->last_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>