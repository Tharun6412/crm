{{-- Show consumer details, tab content --}}

<div class="border rounded-top">
    <div class="bg-primary-subtle p-2 fs-5 fw-semibold">
        <i class="bi bi-person"></i>&nbsp;Consumer Details
    </div>
    <div class="p-2">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header fw-semibold bg-body-secondary">Details</div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <tr>
                                <td class="fw-semibold">Segment</td>
                                <td>{{ $consumer->segment->name }}</td>
                            </tr>
                             <tr>
                                <td class="fw-semibold">CRN</td>
                                <td> {{ $consumer->crn }}</td>
                            </tr>
                             <tr>
                                <td class="fw-semibold">Name</td>
                                <td>{{ $consumer->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Email</td>
                                <td>{{ $consumer->email }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Aadhar</td>
                                <td>{{ maskNumber($consumer->aadhar) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Mobile</td>
                                <td>{{ maskNumber($consumer->phone) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Alternate Mobile</td>
                                <td>{{ maskNumber($consumer->phone_alt) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Status</td>
                                <td><x-consumer.status :status="$consumer->status" /></td>
                            </tr>
                        </table>                   
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header fw-semibold bg-body-secondary">Scheme Details</div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <tr>
                                <td class="fw-semibold">Scheme Name</td>
                                <td>{{ $consumer->scheme?->scheme?->name }}</td>
                            </tr>
                             <tr>
                                <td class="fw-semibold">Registration</td>
                                <td>{{ numberFormat($consumer->scheme?->scheme?->registration) }}</td>
                            </tr>
                             <tr>
                                <td class="fw-semibold">Security Deposit</td>
                                <td>{{ numberFormat($consumer->scheme?->security_deposit) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Consumption Deposit</td>
                                <td>{{ numberFormat($consumer->scheme?->consumption_deposit) }}</td>
                            </tr>
                        </table>                   
                    </div>
                </div>                
                <div class="card mt-3">
                    <div class="card-header fw-semibold bg-body-secondary">Nominee Details</div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <tr>
                                <td class="fw-semibold">Nominee Name</td>
                                <td>{{ $consumer->nominee }}</td>
                            </tr>
                             <tr>
                                <td class="fw-semibold">Nominee Relation</td>
                                <td>{{ $consumer->nomineeRelation->name }}</td>
                            </tr>
                        </table>                   
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header fw-semibold bg-body-secondary">Owner Details</div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <tr>
                                <td class="fw-semibold">Property Type</td>
                                <td class="fw-semibold">
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
                                </td>
                            </tr>
                             <tr>
                                <td class="fw-semibold">Owner Name</td>
                                <td>{{ $consumer->owner_name }}</td>
                            </tr>
                             <tr>
                                <td class="fw-semibold">Owner Phone</td>
                                <td>{{ maskNumber($consumer->owner_phone) }}</td>
                            </tr>
                        </table>                   
                    </div>
                </div>
                 @if ($consumer->segment_id == \App\Enums\SegmentType::DOMESTIC->value)
                <div class="card mt-3">
                    <div class="card-header fw-semibold bg-body-secondary">Tenant Details</div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <tr>
                                <td class="fw-semibold">Tenant Name</td>
                                <td>{{ $consumer->tenant_name }}</td>
                            </tr>
                             <tr>
                                <td class="fw-semibold">Tenant Phone</td>
                                <td>{{ maskNumber($consumer->tenant_phone) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Tenant Email</td>
                                <td>{{ $consumer->tenant_email }}</td>
                            </tr>
                        </table>                   
                    </div>
                </div>                
                @endif

                {{-- <h4 class="text-primary fw-semibold d-none">Details</h4>
                <dl class="row d-none">
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
                <h4 class="text-primary mt-3 fw-semibold d-none">Scheme Details</h4>
                <dl class="row d-none">
                    <dt class="col-sm-3">Scheme Name</dt>
                    <dd class="col-sm-9">{{ $consumer->scheme?->scheme?->name }}</dd>
                    <dt class="col-sm-3">Registration</dt>
                    <dd class="col-sm-9">{{ numberFormat($consumer->scheme?->scheme?->registration) }}</dd>
                    <dt class="col-sm-3">Security Deposit</dt>
                    <dd class="col-sm-9">{{ numberFormat($consumer->scheme?->security_deposit) }}</dd>
                    <dt class="col-sm-3">Consumption Deposit</dt>
                    <dd class="col-sm-9">{{ numberFormat($consumer->scheme?->consumption_deposit) }}</dd>
                </dl>
                <h4 class="text-primary mt-3 fw-semibold d-none">Nominee Details</h4>
                <dl class="row d-none">
                    <dt class="col-sm-3">Nominee Name</dt>
                    <dd class="col-sm-9">{{ $consumer->nominee }}</dd>
                    <dt class="col-sm-3">Nominee Relation</dt>
                    <dd class="col-sm-9">{{ $consumer->nomineeRelation->name }}</dd>
                </dl>
                <h4 class="text-primary mt-3 fw-semibold d-none">Owner Details</h4>
                <dl class="row d-none">
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
                    <h4 class="text-primary mt-3 fw-semibold d-none">Tenant Details</h4>
                    <dl class="row d-none">
                        <dt class="col-sm-3">Tenant Name</dt>
                        <dd class="col-sm-9">{{ $consumer->tenant_name }}</dd>
                        <dt class="col-sm-3">Tenant Phone</dt>
                        <dd class="col-sm-9">{{ maskNumber($consumer->tenant_phone) }}</dd>
                        <dt class="col-sm-3">Tenant Email</dt>
                        <dd class="col-sm-9">{{ $consumer->tenant_email }}</dd>
                    </dl>
                @endif --}}
            </div>
            <div class="col-md-6">                
                <div class="card">
                    <div class="card-header fw-semibold bg-body-secondary">Location</div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <tr>
                                <td class="fw-semibold">Geo Area</td>
                                <td>{{ $consumer->ga->name }}</td>
                            </tr>
                             <tr>
                                <td class="fw-semibold">District</td>
                                <td>{{ $consumer->district->name }}</td>
                            </tr>
                             <tr>
                                <td class="fw-semibold">Charge Area</td>
                                <td>{{ $consumer->ca->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Location</td>
                                <td>{{ $consumer->area->name }}</td>
                            </tr>
                        </table>                   
                    </div>
                </div>                                
                <div class="card mt-3">
                    <div class="card-header fw-semibold bg-body-secondary">Address</div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <tr>
                                <td class="border-0">
                                <address>
                                    <strong>{{ $consumer->name}}</strong><br>
                                    {{ $consumer->cofDisplay?->name }} {{ $consumer->cof_name }}<br>
                                    {{ $consumer->hno }}, {{ $consumer->street }},<br>
                                    {{ $consumer->colony }}, {{ $consumer->city }},<br>
                                    {{ $consumer->district->name ?? '' }}, {{ $consumer->ga->state->name ?? '' }} - {{ $consumer->pincode }}.
                                </address>
                                </td>
                            </tr>
                        </table>                   
                    </div>
                </div>                                               
                <div class="card mt-3">
                    <div class="card-header fw-semibold bg-body-secondary">Meter Details</div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <tr>
                                <td class="fw-semibold">Meter Number</td>
                                <td>{{ $consumer_meter?->meter_no }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Serial Number</td>
                                <td>{{ $consumer_meter?->meter_serial_no }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Initial Reading</td>
                                <td>{{ $consumer_meter?->initial_reading }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Installation Date</td>
                                <td>{{ $consumer_meter?->install_date?->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Installed By</td>
                                <td>{{ $consumer_meter?->installBy?->first_name }}&nbsp;{{ $consumer_meter?->installBy?->last_name }}</td>
                            </tr>
                        </table>                   
                    </div>
                </div>                                               
                <div class="card mt-3">
                    <div class="card-header fw-semibold bg-body-secondary">Additional Details</div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <tr>
                                <td class="fw-semibold">LPG Connections</td>
                                <td>{{ $consumer->lpg_connections }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">DCQ</td>
                                <td>{{ numberFormat($consumer->dcq, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Expected Date</td>
                                <td>{{ $consumer->expected_date?->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Distance&nbsp;(Mts)</td>
                                <td>{{ numberFormat($consumer->distance, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Natural Gas For</td>
                                <td>{{ $consumer->gasRequired?->name }}</td>
                            </tr>
                        </table>                   
                    </div>
                </div>

                {{-- <h4 class="text-primary fw-semibold d-none">Location</h4>
                <dl class="row d-none">
                    <dt class="col-sm-3">Geo Area</dt>
                    <dd class="col-sm-9">{{ $consumer->ga->name }}</dd>
                    <dt class="col-sm-3">District</dt>
                    <dd class="col-sm-9">{{ $consumer->district->name }}</dd>
                    <dt class="col-sm-3">Charge Area</dt>
                    <dd class="col-sm-9">{{ $consumer->ca->name }}</dd>
                    <dt class="col-sm-3">Location</dt>
                    <dd class="col-sm-9">{{ $consumer->area->name }}</dd>
                </dl>
                <h4 class="text-primary mt-3 fw-semibold d-none">Address</h4>
                <address class="d-none">
                    <strong>{{ $consumer->name}}</strong><br>
                    {{ $consumer->cofDisplay?->name }} {{ $consumer->cof_name }}<br>
                    {{ $consumer->hno }}, {{ $consumer->street }},<br>
                    {{ $consumer->colony }}, {{ $consumer->city }},<br>
                    {{ $consumer->district->name ?? '' }}, {{ $consumer->ga->state->name ?? '' }} - {{ $consumer->pincode }}.
                </address>
                <h4 class="text-primary mt-3 fw-semibold d-none">Meter Details</h4>
                <dl class="row d-none">
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
                <h4 class="text-primary mt-3 fw-semiboldc d-none">Additional Details</h4>
                <dl class="row d-none">
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
                </dl> --}}
            </div>
        </div>
        {{-- Status history timeline --}}
        <div class="border-start border-4 border-primary rounded bg-primary-subtle px-3 py-2 mt-3">
            <h4 class="text-dark fw-semibold mt-1">Status Timeline</h4>
        </div>
        <div class="container table-responsive">
            <div class="m-5 ps-4">
                @foreach ($consumer->statusHistory as $history)
                    @php
                        $documents = $documents_list->where('status_id', $history->status_id);
                    @endphp
                    <div class="d-flex position-relative">
                        <!-- Vertical line -->
                        <div class="me-3">
                            <x-consumer.timeline-status :status="$history->status" :created_at="$history->created_at?->format('d-m-Y H:i')" />
                            </div>
                            <!-- Content -->
                            <div class="d-flex gap-3 my-4">
                            <div class="card border border-warning-subtle shadow-sm" style="width: 350px;">
                                <div class="card-body lh-lg p-3">
                                    <div class="row">
                                        <div class="col-4"><x-consumer.status :status="$history->status" /></div>
                                        <div class="col-8 lh-sm"><i class="bi bi-person"></i>&nbsp;{{ $history->createdBy?->first_name }} {{ $history->createdBy?->last_name }}</div>
                                    </div>
                                    <span>
                                        <i class="bi bi-chat-square-text fs-5" title="Notes"></i>&nbsp;{{ $history->notes ?? '' }}<br/>
                                    </span>
                                    @if ($documents->isNotEmpty())
                                        <div class="d-flex flex-wrap gap-2">
                                            <i class="bi bi-files fs-4" title="Files"></i>
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
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>