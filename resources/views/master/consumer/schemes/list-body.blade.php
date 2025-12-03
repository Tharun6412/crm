<div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <strong><i class="bi bi-check2-circle"></i>&nbsp;Success</strong>&nbsp;{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
<form id="schemes-search-form" action="{{ url('master/consumer/schemes') }}" method="GET">
    <div class="d-flex align-items-center justify-content-between pb-2 flex-wrap">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <div>
                <input type="text" name="search_key" id="search_key" class="form-control form-control-sm" placeholder="search here..." value="{{ request()->get('search_key') }}">
            </div>
            <button type="submit" class="btn btn-sm btn-primary" title="Search">
                <i class="bi bi-search"></i>
            </button>
            <a href="{{ url('master/consumer/schemes') }}" class="btn btn-sm btn-warning ajax-link" title="Reset">
                <i class="bi bi-arrow-clockwise"></i>
            </a>
            <span>({{ $schemes->total() }}) Records found</span>
        </div>
        {{-- Right Section --}}
        <div class="d-flex align-items-center gap-2">
            <a href="{{ url('master/consumer/schemes/create') }}" class="btn btn-success btn-sm link-modal">
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
        $i = (($schemes->currentPage() - 1) * $schemes->perPage())+1;
    @endphp
    <!-- Display prospects list -->
    <table class="table table-bordered table-hover page-sort">
        <thead class="table-success">
            <tr>
                <th width="1%" nowrap>S No.</th>
                <th nowrap>
                    <a href="{{ $schemes->appends(['sortBy' => 'name','sortOr' => $sort_order_inverse])->url($schemes->currentPage()) }}">
                        Scheme Name
                        @if ($sort_by == 'name')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th>Total Deposit Amount</th>
                <th>Applicable GAs</th>
                <th>Status</th>
                <th width="2%" nowrap class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($schemes->count() > 0)
                @foreach ($schemes as $scheme)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>
                            <a href="{{ url('master/consumer/schemes/'.$scheme->id) }}" class="link-canvas">{{ $scheme->name }}</a>
                        </td>
                        <td>{{ $scheme->total_deposit }}</td>
                        <td>{{ $scheme->schemesGa->pluck('ga.name')->implode(', ') }}</td>
                        <td>@if ( $scheme->status == 1) <span class="badge bg-success">Enabled</span>
                        @else <span class="badge bg-warning">Disabled</span>
                        @endif</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ url('master/consumer/schemes/'.$scheme->id) }}" class="dropdown-item link-canvas"><i class="bi bi-eye">&nbsp;</i>View</a></li>
                                    <li><a href="{{ url('master/consumer/schemes/'.$scheme->id) }}/edit" class="dropdown-item link-modal"><i class="bi bi-pencil-square">&nbsp;</i>Edit</a></li>
                                    <li>
                                        @if ( $scheme->status == 1) <a class="dropdown-item" href="javascript:statusToggle({{ $scheme->id }})">Disable</a>
                                        @else <a class="dropdown-item" href="javascript:statusToggle({{ $scheme->id }})">Enable</a>
                                        @endif
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
        <div class="col-sm-6">
            <div class="row align-items-center g-1">
                <div class="col-auto">
                    <label for="form-label">Records</label>
                </div>
                <div class="col-auto">
                    <select name="records" id="records" class="form-select" onchange="javascript:$('#schemes-search-form').submit();">
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
            $schemes->appends(['sortBy' => $sort_by, 'sortOr' => $sort_order]);
        @endphp
        {{-- load utils file for pagination --}}
        <div class="col-sm-6">
            {{ $schemes->links('utils.paginator', ['modDiv' => 'schemes-list']) }}
        </div>
    </div>
</form>
@include('scripts.link-modal')
@include('scripts.ajax-form-search', ['form' => 'schemes'])
@include('scripts.link-canvas')

<script>
    function statusToggle(id) {
        if(confirm('Are you sure, you want to toggle the status ?.'))
        {
            $.ajax({
                url: "{{ url('master/consumer/schemes') }}/" + id + "/togglestatus",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function(error) {
                    alert('Something went wrong.');
                }
            });
        }
    }
</script>


