{{-- View Prospect Details --}}
<div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">View Prospect Details</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="action-type">
                @if ($type > 0)
                    @switch($type)
                        @case(1)
                            @include('spot.prospects.status-history.edit')
                            @break
                        @case(2)
                            @include('spot.prospects.documents.create')
                            @break
                        @case(4)
                            @include('spot.prospects.date-request.edit')
                            @break
                        @case(6)
                            @include('spot.prospects.status-history.hold')
                            @break
                        @case(7)
                            @include('spot.prospects.status-history.cancel')
                            @break
                        @case(8)
                            @include('spot.prospects.status-history.ga-approval')
                            @break
                        @default
                    @endswitch
                @endif
            </div>
            <div class="bd-callout bd-callout-primary bg-transparent card mt-0 border-primary mb-3">
                <h4 class="p-3 bg-warning-subtle rounded-2">Prospect Data&nbsp;-&nbsp;{{ $prospect->code }}</h4>
                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12 border-bottom">
                        <label for="prospectcode" class="form-label fw-semibold">Prospect Code:</label>
                        <p>{{ $prospect->code }}</p>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 border-bottom">
                        <label for="prospectname" class="form-label fw-semibold">Prospect Name:</label>
                        <p>{{ $prospect->name }}</p>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 border-bottom">
                        <label for="prospecttype" class="form-label fw-semibold">Prospect Type:</label>
                        <p>{{ $prospect->segment->name }}</p>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 border-bottom pt-2">
                        <label for="industrialarea" class="form-label fw-semibold">Industrial Area:</label>
                        <p>{{ $prospect->industrialArea->name }}</p>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 border-bottom pt-2">
                        <label for="zone" class="form-label fw-semibold">Zone (GA):</label>
                        <p>{{ $prospect->zone }}</p>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 border-bottom pt-2">
                        <label for="segment" class="form-label fw-semibold">Segment:</label>
                        <p>{{ $prospect->firm->name }}</p>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 border-bottom pt-2">
                        <label for="fuelmajor" class="form-label fw-semibold">Current Fuel Major:</label>
                        <p>{{ $prospect->fuelType->name }}</p>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 border-bottom pt-2">
                        <label for="fuelconsumption" class="form-label fw-semibold">Current fuel Consumption per day:</label>
                        <p>
                            {{ $prospect->fuel_consumption }}
                            @switch($prospect->unit_id)
                                @case(1)
                                    {{ "Liters" }}
                                    @break
                                @case(2)
                                    {{ "KGs" }}
                                    @break
                                @case(3)
                                    {{ "Tons" }}
                                    @break
                                @default
                            @endswitch
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 border-bottom pt-2">
                        <label for="scmd" class="form-label fw-semibold">Natural Gas Potential (SCMD):</label>
                        <p>{{ $prospect->potential }}</p>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 border-bottom pt-2">
                        <label for="expected_date" class="form-label fw-semibold">Gas Service Expected Date:</label>
                        <p>{{ $prospect->expected_date?->format('d-m-Y') }}</p>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 border-bottom pt-2">
                        <label for="industrial_gate" class="form-label fw-semibold">NG Pipeline available at the Industrial gate?</label>
                        <p>
                            @if (!empty($prospect->pipeline_availability))
                                {{ $prospect->pipeline_availability=="2" ? "No" : "Yes" }}
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 border-bottom pt-2">
                        <label for="Latitude" class="form-label fw-semibold">Latitude:</label>
                        <p>{{ $prospect->latitude }}</p>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 border-bottom pt-2">
                        <label for="Longitude" class="form-label fw-semibold">Longitude:</label>
                        <p>{{ $prospect->longitude }}</p>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 border-bottom pt-2">
                        <label for="geoarea" class="form-label fw-semibold">Geo Area:</label>
                        <p>{{ $prospect->ga->name }}</p>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 border-bottom pt-2">
                        <label for="cluster" class="form-label fw-semibold">Cluster:</label>
                        <p>{{ $prospect->cluster->name }}</p>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 border-bottom pt-2">
                        <label for="statename" class="form-label fw-semibold">State:</label>
                        <p>{{ $prospect->state->name }}</p>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 border-bottom pt-2">
                        <label for="clusterhead" class="form-label fw-semibold">Cluster Head:</label>
                        <p>{{ $prospect->clusterHead->first_name }}&nbsp;
                            {{ $prospect->clusterHead->last_name }}</p>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 border-bottom pt-2">
                        <label for="gahead" class="form-label fw-semibold">GA Head:</label>
                        <p>{{ $prospect->gaHead->first_name }}&nbsp;{{ $prospect->gaHead->last_name }}</p>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 border-bottom pt-2">
                        <label for="salesofficer" class="form-label fw-semibold">Sales Officer:</label>
                        <p>{{ $prospect->salesOfficer->first_name }}&nbsp;
                            {{ $prospect->salesOfficer->last_name }}</p>
                    </div>                    
                    <div class="col-md-4 col-sm-12 col-xs-12 border-bottom pt-2">
                        <label for="stagestatus" class="form-label fw-semibold">Stage:</label>
                        <p><x-spot.stages :stage="$prospect->stage" type="1" /></p>
                    </div>                    
                    <div class="col-md-4 col-sm-12 col-xs-12 border-bottom pt-2">
                        <label for="substagestatus" class="form-label fw-semibold">Sub-stage:</label>
                        <p><x-spot.stages :stage="$prospect->stage" type="2" /></p>
                    </div>                    
                    <div class="col-md-4 col-sm-12 col-xs-12 pt-2">
                        <label for="stage_status" class="form-label fw-semibold">Status:</label>
                        <p><x-spot.status :status="$prospect->statusType" /></p>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 pt-2">
                        <label for="lupdateddate" class="form-label fw-semibold">Last Status Updated Date:</label>
                        <p>
                            @php
                                if (isset($prospect['status_date'])) {
                                    $status_date = strtotime($prospect['status_date']);
                                    $formatted_date = date('d-m-Y', $status_date);
                                    $days_diff = floor((time() - $status_date) / (60 * 60 * 24));
                                    echo $formatted_date . " (" . $days_diff . " days ago)";
                                } else {
                                    echo '--';
                                }
                            @endphp
                        </p>
                    </div>

                    {{-- <div class="col-md-6 col-sm-12 col-xs-12 d-none">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-3 d-none">
                                <tbody>
                                    <tr>
                                        <td width="270" class="text-end">Prospect Code</td>
                                        <td width="1%">:</td>
                                        <td>{{ $prospect->code }}</td>
                                    </tr>
                                    <tr>
                                        <td width="150" class="text-end">Prospect Name</td>
                                        <td width="1%">:</td>
                                        <td>{{ $prospect->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">Prospect Type</td>
                                        <td>:</td>
                                        <td>{{ $prospect->segment->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">Industrial Area</td>
                                        <td>:</td>
                                        <td>{{ $prospect->industrialArea->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">Zone (GA)</td>
                                        <td>:</td>
                                        <td>{{ $prospect->zone }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">Segment </td>
                                        <td>:</td>
                                        <td>{{ $prospect->firm->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">Current Fuel Major</td>
                                        <td>:</td>
                                        <td>{{ $prospect->fuelType->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">Current fuel Consumption per day</td>
                                        <td>:</td>
                                        <td>{{ $prospect->fuel_consumption }}
                                            @switch($prospect->unit_id)
                                                @case(1)
                                                    {{ "Liters" }}
                                                    @break
                                                @case(2)
                                                    {{ "KGs" }}
                                                    @break
                                                @case(3)
                                                    {{ "Tons" }}
                                                    @break
                                                @default
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">Natural Gas Potential (SCMD)</td>
                                        <td>:</td>
                                        <td>{{ $prospect->potential }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">Gas Service Expected Date</td>
                                        <td>:</td>
                                        <td>{{ $prospect->expected_date?->format('d-m-Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">NG Pipeline available at the Industrial gate?</td>
                                        <td>:</td>
                                        <td>
                                            @if (!empty($prospect->pipeline_availability))
                                                {{ $prospect->pipeline_availability=="2" ? "No" : "Yes" }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">Latitude</td>
                                        <td>:</td>
                                        <td>{{ $prospect->latitude }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">Longitude</td>
                                        <td>:</td>
                                        <td>{{ $prospect->longitude }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="text-end" width="270">Geo Area</td>
                                        <td width="1%">:</td>
                                        <td>{{ $prospect->ga->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">Cluster</td>
                                        <td>:</td>
                                        <td>{{ $prospect->cluster->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">State</td>
                                        <td>:</td>
                                        <td>{{ $prospect->state->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end" width="270">Cluster Head</td>
                                        <td width="1%">:</td>
                                        <td>{{ $prospect->clusterHead->first_name }}&nbsp;{{ $prospect->clusterHead->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">GA Head</td>
                                        <td>:</td>
                                        <td>{{ $prospect->gaHead->first_name }}&nbsp;{{ $prospect->gaHead->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">Sales Officer</td>
                                        <td>:</td>
                                        <td>{{ $prospect->salesOfficer->first_name }}&nbsp;{{ $prospect->salesOfficer->last_name }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12 d-none">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-3">
                                <tbody>
                                    <tr>
                                        <td class="text-end">Stage</td>
                                        <td>:</td>
                                        <td>
                                            <x-spot.stages :stage="$prospect->stage" type="1" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">Sub-stage</td>
                                        <td>:</td>
                                        <td>
                                            <x-spot.stages :stage="$prospect->stage" type="2" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="210" class="text-end">Status</td>
                                        <td width="1%">:</td>
                                        <td><x-spot.status :status="$prospect->statusType" /></td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">Last Status Updated Date</td>
                                        <td>:</td>
                                        <td>
                                            @php
                                                if (isset($prospect['status_date'])) {
                                                    $status_date = strtotime($prospect['status_date']);
                                                    $formatted_date = date('d-m-Y', $status_date);
                                                    $days_diff = floor((time() - $status_date) / (60 * 60 * 24));
                                                    echo $formatted_date . " (" . $days_diff . " days ago)";
                                                } else {
                                                    echo '--';
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless mb-3">
                                <tbody>    
                                    <tr>
                                        <td class="text-end" width="210">Created By</td>
                                        <td width="1%">:</td>
                                        <td>{{ $prospect->createdBy->first_name." ".$prospect->createdBy->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">Created Date</td>
                                        <td>:</td>
                                        <td>{{ $prospect->created_at->format('d-m-Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">Updated Date</td>
                                        <td>:</td>
                                        <td>{{ $prospect->updated_at->format('d-m-Y') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> --}}
                </div>
            </div>
            <div id="prospect-documents">
                @include('spot.prospects.documents.list')
            </div>
            <div id="prospect-pipeline">
                @include('spot.prospects.pipeline.list')
            </div>
            <div id="date-request">
                @include('spot.prospects.date-request.list')
            </div>
            <div id="status-history">
                @include('spot.prospects.status-history.list')
            </div>
            <div id="comments">
                @include('spot.prospects.comments.comments')
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>