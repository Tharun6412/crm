<form id="tr-search-form" method="GET" action="{{ url('consumers/tr') }}">
    <div class="d-flex align-items-center justify-content-between pb-2 flex-wrap">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <div>
                <input type="text" name="search_key" id="search_key" class="form-control form-control-sm" placeholder="search here..." value="{{ request()->get('search_key') }}">
            </div>
            <button type="submit" class="btn btn-sm btn-primary" title="Search">
                <i class="bi bi-search"></i>
            </button>
            <a href="{{ url('consumers/tr') }}" class="btn btn-sm btn-warning ajax-link" title="Reset">
                <i class="bi bi-arrow-clockwise"></i>
            </a>
            <span>({{ $consumers->total() }}) Records found</span>
        </div>
        {{-- Right Section --}}
        <div class="d-flex align-items-center gap-2">
            @if ($consumers->count() > 0)    
                <a href="{{ url('consumers') }}?{{ http_build_query(request()->all()) }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-file-earmark-excel"></i>&nbsp;Export
                </a>
            @endif
        </div>
    </div> 
    {{-- Pagination and Page Sorting --}}
    @php
        $sort_by = (request()->has('sortBy')) ? request()->get('sortBy') : 'created_at';
        $sort_order = (request()->has('sortOr')) ? request()->get('sortOr') : 'desc';
        $sort_order_inverse = ($sort_order == 'asc') ? 'desc' : 'asc';
        $sort_icon = ($sort_order == 'asc') ? 'bi-caret-down-fill' : 'bi-caret-up-fill';
        $i = (($consumers->currentPage() - 1) * $consumers->perPage())+1;
    @endphp
    <div class="teable-responsive">
        <table class="table table-bordered page-sort">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th class="text-nowrap">CRN</th>
                    <th class="text-nowrap">Name</th>
                    <th>Consumer Type</th>
                    <th>Status</th>
                    <th>Status Date</th>
                    <th class="text-nowrap">Geo Area</th>
                    <th class="text-nowrap">District</th>
                    <th class="text-nowrap">Scheme Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if ($consumers->count() > 0)
                    @foreach ($consumers as $consumer)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $consumer->t_crn }}</td>
                            <td>{{ $consumer->titleDisplay->name }}&nbsp;{{ $consumer->fname }}&nbsp;{{ $consumer->lname }}</td>
                            <td>{{ $consumer->segment->name }}</td>
                            <td>{{ $consumer->status->name }}</td>
                            <td></td>
                            <td>{{ $consumer->ga->name }}</td>
                            <td>{{ $consumer->district->name }}</td>
                            <td></td>
                            <td class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="actionDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ url('consumers/'.$consumer->id) }}">
                                            <i class="bi bi-info-circle"></i>&nbsp;View
                                        </a>
                                        @if ($consumer->status_id == 1)    
                                            <a class="dropdown-item link-modal" href="{{ url('consumers/trPayment/'.$consumer->id.'/edit') }}">
                                                <i class="bi bi-info-circle"></i>&nbsp;Pay Deposit
                                            </a>
                                        @endif
                                    </li>
                                </ul>
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
                        <select name="records" id="records" class="form-select" onchange="javascript:$('#consumers-search-form').submit();">
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
                $consumers->appends(['sortBy' => $sort_by, 'sortOr' => $sort_order]);
            @endphp
            {{-- load utils file for pagination --}}
            <div class="col-sm-6">
                {{ $consumers->links('utils.paginator', ['modDiv' => 'tr-list']) }}
            </div>
        </div>
    </div>
</form>
@include('scripts.link-modal')
@include('scripts.ajax-form-search', ['form' => 'tr'])
