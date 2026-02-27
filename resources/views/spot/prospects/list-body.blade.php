<div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <strong><i class="bi bi-check2-circle"></i>&nbsp;Success</strong>&nbsp;{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
@php
    use \App\Enums\SpotStatus;
@endphp
<form id="prospects-search-form" action="{{ url('spot/prospects') }}" method="GET">
    <div class="d-flex align-items-center justify-content-between pb-2 flex-wrap">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <div>
                <input type="text" name="search_key" id="search_key" class="form-control form-control-sm" placeholder="search here..." value="{{ request()->get('search_key') }}">
            </div>
            <button type="submit" class="btn btn-sm btn-primary" title="Search">
                <i class="bi bi-search"></i>
            </button>
            <a href="{{ url('spot/prospects') }}" class="btn btn-sm btn-warning ajax-link" title="Reset">
                <i class="bi bi-arrow-clockwise"></i>
            </a>
            <span>({{ $prospects->total() }}) Records found</span>
        </div>
        {{-- Right Section --}}
        <div class="d-flex align-items-center gap-2">
            @if ($prospects->count() > 0)    
                <a href="{{ url('spot/prospects/prospectsExport') }}?{{ http_build_query(request()->all()) }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-file-earmark-excel"></i>&nbsp;Export
                </a>
            @endif
            <a href="{{ url('spot/prospects/create') }}" class="btn btn-success btn-sm link-modal">
                <i class="bi bi-plus-lg"></i>&nbsp;Create
            </a>
        </div>
    </div>
    {{-- Parameters for sorting By column and Order --}}
    @php
        $sort_by = (request()->has('sortBy')) ? request()->get('sortBy') : 'created_at';
        $sort_order = (request()->has('sortOr')) ? request()->get('sortOr') : 'desc';
        $sort_order_inverse = ($sort_order == 'asc') ? 'desc' : 'asc';
        $sort_icon = ($sort_order == 'asc') ? 'bi-caret-down-fill' : 'bi-caret-up-fill';
        $i = (($prospects->currentPage() - 1) * $prospects->perPage())+1;
    @endphp
    <!-- Display prospects list -->
    <table class="table table-bordered page-sort">
        <thead>
            <tr>
                <th nowrap>S No.</th>
                <th nowrap>GA
                    <x-master.ga-filter/>
                </th>
                <th nowrap>Segment <x-master.segment-filter/></th>
                <th nowrap>
                    <a href="{{ $prospects->appends(['sortBy' => 'name','sortOr' => $sort_order_inverse])->url($prospects->currentPage()) }}">
                        Prospect Name
                        @if ($sort_by == 'name')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap>Industrial Area     
                    <x-master.industrial-area-filter/>
                </th>
                <th nowrap>Current Fuel
                    <x-master.current-fuel-filter/>
                </th>
                <th class="text-end">
                    <a href="{{ $prospects->appends(['sortBy' => 'potential','sortOr' => $sort_order_inverse])->url($prospects->currentPage()) }}">
                        Natural Gas<br/>Potential (SCMD)
                        @if ($sort_by == 'potential')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap>
                    <a href="{{ $prospects->appends(['sortBy' => 'expected_date','sortOr' => $sort_order_inverse])->url($prospects->currentPage()) }}">
                        Gas service<br/>expected date
                        @if ($sort_by == 'expected_date')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                    <x-master.date-filter/>
                </th>
                <th nowrap class="text-center">Stage
                    <x-spot.stage-filter :stages="$stages"/>
                </th>
                <th nowrap class="text-center">Sub Stage
                    <x-spot.sub-stage-filter :stages="$stages"/>
                </th>
                <th npwrap>Status
                    <x-spot.status-filter :status="$status_list"/>
                </th>
                <th nowrap class="text-center">
                    <a href="{{ $prospects->appends(['sortBy' => 'status_date','sortOr' => $sort_order_inverse])->url($prospects->currentPage()) }}">
                        Last Status date
                        @if ($sort_by == 'status_date')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($prospects->count() > 0)
                @foreach ($prospects as $prospect)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $prospect->ga->name }}</td>
                        <td>{{ $prospect->segment->name }}</td>
                        <td>
                            <a href="{{ url('spot/prospects/'.$prospect->id) }}" class="link-modal">{{ $prospect->name }}</a>
                        </td>
                        <td>{{ $prospect->industrialArea->name }}</td>
                        <td>{{ $prospect->fuelType->name }}</td>
                        <td>{{ $prospect->potential }}</td>
                        <td>{{ $prospect->expected_date?->format('d-m-Y') }}</td>
                        <td>{{ $prospect->stage->parent->name ?? '' }}</td>
                        <td>{{ $prospect->stage->name }}</td>
                        <td>{{ $prospect->statusType->name }}</td>
                        <td>{{ $prospect->status_date->format('d-m-Y') }}</td>
                        <td>
                            {{-- Prospects Actions Dropdown --}}
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="actionDropdown">
                                    <li>
                                        <a class="dropdown-item link-modal" href="{{ url('spot/prospects/'.$prospect->id) }}">
                                            <i class="bi bi-info-circle"></i>&nbsp;View
                                        </a>
                                    </li>
                                    {{-- Check Pipeline Availability --}}
                                    @if (isPipeLineAvailable($prospect->pipeline_availability, $prospect->status_id))
                                        <li>
                                            <a class="dropdown-item link-modal" href="{{ url('spot/prospect/pipeline/'.$prospect->id.'/edit') }}"><i class="bi bi-folder2-open"></i>&nbsp;Manage PipeLine</a>
                                        </li>
                                    @endif
                                    {{-- Check user not in Hold Status --}}
                                    @if (checkProspectHold($prospect->status_id)) 
                                        @if (isInProgress($prospect->status_id) AND (isAdmin() OR isGaHead() OR isClusterHead() OR isSalesofficer()) AND (in_array($prospect->ga_id, session()->get('user')['gas'])))    
                                            <li>
                                                <a class="dropdown-item link-modal" href="{{ url('spot/prospects/'.$prospect->id.'/edit') }}">
                                                    <i class="bi bi-pencil"></i>&nbsp;Edit
                                                </a>
                                            </li>
                                        @endif
                                        @if (isInProgress($prospect->status_id) OR isApproved($prospect->status_id) OR isClosedWon($prospect->stage_id))    
                                            <li>
                                                <a class="dropdown-item link-modal" href="{{ url('spot/prospectStatus/editStatus/'.$prospect->id) }}">
                                                    <i class="bi bi-check2-circle"></i>&nbsp;Update Status
                                                </a>
                                            </li>
                                        @endif
                                        {{-- Check Prospect is in Progress --}}
                                        @if (isInProgress($prospect->status_id))    
                                            <li>
                                                <a class="dropdown-item link-modal" href="{{ url('spot/dateChangeRequest/create/'.$prospect->id) }}">
                                                    <i class="bi bi-info-circle"></i>&nbsp;Request For Date Change
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item link-modal" href="{{ url('spot/prospectDocument/create/'.$prospect->id) }}">
                                                    <i class="bi bi-folder2-open"></i>&nbsp;Manage Documents
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item link-modal" href="{{ url('spot/prospects/'.$prospect->id) }}">
                                                    <i class="bi bi-chat"></i>&nbsp;Add Comment
                                                </a>
                                            </li>
                                        {{-- Check Prospect is in Request for Approval Status --}}
                                        @elseif (isRequestForApproval($prospect->status_id))
                                            @if (isAdmin() OR isGaHead() OR isClusterHead() OR isSalesOfficer())    
                                                <li>
                                                    <a class="dropdown-item link-modal" href="{{ url('spot/prospectStatus/gaApprove/'.$prospect->id) }}">
                                                        <i class="bi bi-check2-circle"></i>&nbsp;Ga Approval
                                                    </a>
                                                </li>
                                            @endif
                                            <li>
                                                <a class="dropdown-item link-modal" href="{{ url('spot/prospectDocument/create/'.$prospect->id) }}">
                                                    <i class="bi bi-folder2-open"></i>&nbsp;Manage Documents
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item link-modal" href="{{ url('spot/prospects/'.$prospect->id) }}">
                                                    <i class="bi bi-chat"></i>&nbsp;Add Comment
                                                </a>
                                            </li>
                                        {{-- No Action performed when Closed Lost --}}
                                        @elseif($prospect->status_id == SpotStatus::CLOSED_LOST->value)
                                        @else
                                            @if ($prospect->status_id == SpotStatus::HOLD->value)    
                                                <li>
                                                    <a class="dropdown-item" id="unhold_status" href="{{ url('spot/prospectStatus/unHold/'.$prospect->id) }}">
                                                        <i class="bi bi-ban"></i>&nbsp;UnHold
                                                    </a>
                                                </li>
                                            @endif
                                        @endif
                                    @endif
                                    {{-- Hold will be available when prospect not in InProgress, ClosedLost,HOLD,CANCEL --}}
                                    @if ($prospect->status_id != SpotStatus::IN_PROGRESS->value AND $prospect->status_id != SpotStatus::CLOSED_LOST->value AND $prospect->status_id != SpotStatus::HOLD->value AND $prospect->status_id != SpotStatus::CANCEL->value)
                                        <li>
                                            <a class="dropdown-item link-modal" href="{{ url('spot/prospectStatus/hold/'.$prospect->id) }}">
                                                <i class="bi bi-ban"></i>&nbsp;Hold
                                            </a>
                                        </li>
                                        @if (isAdmin() OR isClusterHead())    
                                            <li>
                                                <a class="dropdown-item link-modal" href="{{ url('spot/prospectStatus/cancel/'.$prospect->id) }}">
                                                    <i class="bi bi-x-circle"></i>&nbsp;Cancel/Delete
                                                </a>
                                            </li>
                                        @endif
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="11">No records found</td>
                </tr>
            @endif
        </tbody>
    </table>
    <div class="row">
        <div class="col-sm-6">
            <div class="row align-items-center g-1">
                <div class="col-auto">
                    <label for="form-label">Records</label>
                </div>
                <div class="col-auto">
                    <select name="records" id="records" class="form-select" onchange="javascript:$('#prospects-search-form').submit();">
                        <option value="10" @selected(request()->get('records') == 10)>10</option>
                        <option value="20" @selected(request()->get('records') == 20)>20</option>
                        <option value="50" @selected(request()->get('records') == 50)>50</option>
                        <option value="100" @selected(request()->get('records') == 100)>100</option>
                    </select>
                </div>
            </div>
        </div>
        {{--  Reset pagination parameters for paginator --}}
        @php
            $prospects->appends(['sortBy' => $sort_by, 'sortOr' => $sort_order]);
        @endphp
        {{-- load utils file for pagination --}}
        <div class="col-sm-6">
            {{ $prospects->links('utils.paginator', ['modDiv' => 'prospects-list']) }}
        </div>
    </div>
</form>
<script type="text/javascript">
    function reloadProspects() {
        $.get("{{ url('spot/prospects') }}", function(data) {
            $('#prospects-list').html(data);
        });
    }
</script>
@include('scripts.link-modal')
@include('scripts.ajax-form-search', ['form' => 'prospects'])
@include('scripts.ajax-link-id-change', ['mod' => 'unhold_status', 'msg' => 'Are you sure you want to unhold the status.', 'callback' => 'reloadProspects()'])


