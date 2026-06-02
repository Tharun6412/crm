<div>
    <div class="d-flex align-items-center justify-content-between pb-2 flex-wrap">
        <div class="d-flex align-items-center gap-1 flex-wrap">
            <div>
                <input type="text" name="search_key" id="search_key" class="form-control" placeholder="search here..." value="{{ request()->get('search_key') }}">
            </div>
            <button type="submit" class="btn btn-primary" title="Search">
                <i class="bi bi-search"></i>
            </button>
            <a href="{{ url('pngrb/applications') }}" class="btn btn-warning ajax-link" title="Reset">
                <i class="bi bi-arrow-clockwise"></i>
            </a>
            <span class="fw-semibold">({{ $applications->total() }}) Records found</span>
        </div>
        {{-- Right Section --}}
        {{-- <div class="d-flex align-items-center gap-2">
            @if ($applications->count() > 0)    
                <a href="{{ url('spot/prospects/prospectsExport') . '?' . http_build_query(request()->query()) }}" class="btn btn-outline-primary" action="exprt">
                    <i class="bi bi-file-earmark-excel"></i>&nbsp;Export
                </a>
            @endif
        </div> --}}
    </div>
    {{-- Parameters for sorting By column and Order --}}        
    @php
        $sort_by = (request()->has('sortBy')) ? request()->get('sortBy') : 'created_at';
        $sort_order = (request()->has('sortOr')) ? request()->get('sortOr') : 'desc';
        $sort_order_inverse = ($sort_order == 'asc') ? 'desc' : 'asc';
        $sort_icon = ($sort_order == 'asc') ? 'bi-caret-down-fill' : 'bi-caret-up-fill';
        $i = (($applications->currentPage() - 1) * $applications->perPage())+1;
    @endphp
</div>
<div class="table-responsive">
    <table class="table table-bordered page-sort table-hover table-striped align-middle">
        <thead>
            <tr>
                <td>S.No</td>
                <td>
                    <a href="{{ $applications->appends(['sortBy' => 'applicationNumber','sortOr' => $sort_order_inverse])->url($applications->currentPage()) }}">
                        Application Number
                        @if ($sort_by == 'applicationNumber')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </td>
                <td>
                    <a href="{{ $applications->appends(['sortBy' => 'name','sortOr' => $sort_order_inverse])->url($applications->currentPage()) }}">
                        Name
                        @if ($sort_by == 'name')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </td>
                <td>Mobile Number</td>
                <td>Email</td>
                <td>State</td>
                <td>District</td>
                <td>
                    <a href="{{ $applications->appends(['sortBy' => 'created_at','sortOr' => $sort_order_inverse])->url($applications->currentPage()) }}">
                        Added Date
                        @if ($sort_by == 'created_at')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </td>
                <td>Actions</td>
            </tr>
        </thead>
        <tbody>
            @if ($applications->count() > 0)
                @foreach ($applications as $application)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $application?->applicationNumber }}</td>
                        <td>{{ $application?->name }}</td>
                        <td>{{ $application?->mobileNumber }}</td>
                        <td>{{ $application?->email }}</td>
                        <td>{{ $application?->state }}</td>
                        <td>{{ $application?->district }}</td>
                        <td>{{ $application->created_at->format('d-m-Y') }}</td>
                        <td>
                            <a type="button" class="btn btn-info btn-sm link-modal" href="{{ url('pngrb/applications/'.$application->id) }}">View</a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10">No applications found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="row align-items-center g-1">
            <div class="col-auto">
                <label for="form-label">Records</label>
            </div>
            <div class="col-auto">
                <select name="records" id="records" class="form-select" onchange="javascript:$('#applications-search-form').submit();">
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
        $applications->appends(['sortBy' => $sort_by, 'sortOr' => $sort_order]);
    @endphp
    {{-- load utils file for pagination --}}
    <div class="col-sm-6">
        {{ $applications->links('utils.paginator', ['modDiv' => 'pngrb-list']) }}
    </div>
</div>
@include('scripts.link-modal')
