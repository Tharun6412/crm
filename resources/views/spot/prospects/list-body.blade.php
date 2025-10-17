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
            <tr class="spot-table-bg">
                <th nowrap>S No.</th>
                <th nowrap>
                    <a href="javascript:void(0)">
                        GA</a>
                </th>
                <th nowrap>
                    <a href="javascript:void(0)">
                        Prospect Name
                    </a>
                </th>
                <th nowrap>
                    <a href="javascript:void(0)">
                        Industrial Area
                    </a>
                </th>
                <th nowrap>
                    <a href="javascript:void(0)">
                        Current Fuel
                    </a>
                </th>
                <th class="text-end">
                    <a href="javascript:void(0)">
                        Natural Gas<br/>Potential (SCMD)
                    </a>
                </th>
                <th nowrap>
                    <a href="javascript:void(0)">
                        Gas service<br/>expected date</a>
                </th>
                <th nowrap class="text-center">
                    <a href="javascript:void(0)">
                        Stage</a>
                </th>
                <th nowrap class="text-center">
                    <a href="javascript:void(0)">
                        Sub Stage</a>
                </th>
                <th nowrap class="text-center">
                    <a href="javascript:void(0)">
                        Last Status date</a>
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
                        <td>
                            <a href="{{ url('spot/prospects/'.$prospect->id) }}" class="link-modal">{{ $prospect->name }}</a>
                        </td>
                        <td>{{ $prospect->industrialArea->name }}</td>
                        <td>{{ $prospect->fuelType->name }}</td>
                        <td>{{ $prospect->potential }}</td>
                        <td>{{ $prospect->expected_date->format('d-m-Y') }}</td>
                        <td>{{ $prospect->stageType->name }}</td>
                        <td>{{ $prospect->subStage->name }}</td>
                        <td>{{ $prospect->status_date->format('d-m-Y') }}</td>
                        <td>
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
                                    <li>
                                        <a class="dropdown-item link-modal" href="{{ url('spot/prospects/'.$prospect->id.'/edit') }}">
                                            <i class="bi bi-pencil"></i>&nbsp;Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item link-modal" href="{{ url('spot/prospectDateChangeRequest/create/'.$prospect->id) }}">
                                            <i class="bi bi-info-circle"></i>&nbsp;Request For Date Change
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item link-modal" href="{{ url('spot/prospects/editStatus/'.$prospect->id) }}">
                                            <i class="bi bi-check2-circle"></i>&nbsp;Update Status
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
                                    <li>
                                        <a class="dropdown-item link-modal" href="{{ url('spot/prospects/holdStatus/'.$prospect->id) }}">
                                            <i class="bi bi-ban"></i>&nbsp;Hold
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item link-modal" href="{{ url('spot/prospects/cancelStatus/'.$prospect->id) }}">
                                            <i class="bi bi-x-circle"></i>&nbsp;Cancel
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="deleteProspectById(230)">
                                            <i class="bi bi-trash"></i>&nbsp;Delete
                                        </a>
                                    </li>
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
        <div class="col-sm-6"></div>
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
@include('scripts.link-modal')
@include('scripts.ajax-form-search', ['form' => 'prospects'])

