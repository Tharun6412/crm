{{-- View Prospect Details --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">View Prospect Details</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="bd-callout bd-callout-primary bg-transparent card mt-0 border-primary mb-3">
                <h4>Prospect Data&nbsp;-&nbsp;{{ $prospect->code }}</h4>
                <div class="row">
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-3">
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
                                        <td>{{ $prospect->segment_id }}</td>

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
                                        <td class="text-end">Current fuel Comsumption per day</td>
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
                                        <td>{{ $prospect->expected_date->format('d-m-Y') }}</td>
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-3">
                                <tbody>
                                    <tr>
                                        <td class="text-end">Stage</td>
                                        <td>:</td>
                                        <td>{{ $prospect->stageType->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">Sub-stage</td>
                                        <td>:</td>
                                        <td>{{ $prospect->substage->name }}</td>
                                    </tr>
                                    <tr>
                                        <td width="210" class="text-end">Status</td>
                                        <td width="1%">:</td>
                                        <td>{{ $prospect->statusType->name }}</td>
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
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>