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
        <div class="container">
            <div class="m-5 ps-4">
                @foreach ($consumer->statusHistory as $history)
                    @php
                        $documents = $documents_list->where('status_id', $history->status_id);
                    @endphp
                    <div class="d-flex position-relative">
                        <!-- Vertical line -->
                        <div class="me-3">
                            <div class="border-start border-3 border-primary h-100 position-relative">             
                                <!-- Dot -->
                                <div class="position-absolute top-0 start-0 translate-middle badge rounded-pill border-primary border text-dark p-2 bg-white">
                                    <i class="bi bi-calendar3">&nbsp;</i>{{ $history->created_at?->format('d-m-Y H:i') }}
                                </div>
                            </div>
                        </div>
                        <!-- Content -->
                        <div class="d-flex gap-3 mb-3 mt-3 ps-1 pt-3 pb-3 pe-2">
                            <div class="card border border-warning shadow-sm">
                                <div class="card-body lh-lg">
                                        <x-consumer.status :status="$history->status" />&nbsp;&nbsp;&nbsp;
                                            <span><i class="bi bi-person">&nbsp;</i>
                                                {{ $history->createdBy?->first_name }}
                                        <br/><i class="bi bi-chat-dots">&nbsp;</i>{{ $history->notes }}<br/>
                                        {{ $history->createdBy?->last_name }}</span>
                                        @if ($documents->isNotEmpty())
                                            <div class="d-flex flex-wrap gap-3">
                                                @foreach ($documents as $doc)
                                                    <div>   
                                                        @php
                                                            $ext = strtolower(pathinfo($doc->file->file_name, PATHINFO_EXTENSION));
                                                            $icons = [
                                                                'pdf' => 'bi-file-earmark-pdf',
                                                                'doc' => 'bi-file-earmark-word',
                                                                'docx' => 'bi-file-earmark-word',
                                                                'xls' => 'bi-file-spreadsheet',
                                                                'xlsx' => 'bi-file-spreadsheet',
                                                                'jpg' => 'bi-file-image',
                                                                'jpeg' => 'bi-file-image',
                                                                'png' => 'bi-file-image',
                                                            ];
                                                        @endphp
                                                        <a href="{{ url('master/dc/documents/' . $doc->file->id) }}"
                                                        target="_blank"
                                                        class="fs-4 text-decoration-none" title="{{ $doc->docType->name ?? 'Not Specified' }} - {{ $doc->file->file_name }}">
                                                            <i class="bi {{ $icons[$ext] ?? 'bi-file' }}"></i>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif 
                                </div>
                            </div>
                            <!-- Documents -->
                            {{-- @if ($documents->isNotEmpty())
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach ($documents as $doc)
                                        <div class="card border-info shadow-sm" style="width: 12rem;">
                                            <div class="card-header bg-info-subtle">
                                                {{ $doc->docType->name ?? 'Not Specified' }}
                                            </div>

                                            <div class="card-body text-center position-relative">
                                                @php
                                                    $ext = strtolower(pathinfo($doc->file->file_name, PATHINFO_EXTENSION));
                                                    $icons = [
                                                        'pdf' => 'bi-file-earmark-pdf',
                                                        'doc' => 'bi-file-earmark-word',
                                                        'docx' => 'bi-file-earmark-word',
                                                        'xls' => 'bi-file-spreadsheet',
                                                        'xlsx' => 'bi-file-spreadsheet',
                                                        'jpg' => 'bi-file-image',
                                                        'jpeg' => 'bi-file-image',
                                                        'png' => 'bi-file-image',
                                                    ];
                                                @endphp

                                                <a href="{{ url('master/dc/documents/' . $doc->file->id) }}"
                                                target="_blank"
                                                class="fs-1 text-decoration-none">
                                                    <i class="bi {{ $icons[$ext] ?? 'bi-file' }}"></i>
                                                </a>
                                                <div class="fs-sm">
                                                    <span class="text-secondary small">
                                                        <i class="bi bi-calendar3"></i>
                                                        {{ $doc->created_at?->format('d-m-Y H:i:s') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif --}}

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>