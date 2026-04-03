{{-- Show Call details, tab content --}}

<div class="border rounded-top">
    <div class="bg-primary-subtle p-2 fs-5 fw-semibold">
        <i class="bi bi-telephone-inbound"></i>&nbsp;Calls&nbsp;-&nbsp;({{ $complaints->total() }})
    </div>
    <div class="p-2">
        @if ($complaints->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-primary">
                    <thead class="table-primary">
                        <tr>
                            <th width="1%" nowrap>S.No</th>
                            <th>Complaint Number</th>
                            <th>Category</th>
                            <th>Raised Date</th>
                            <th>Closed Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sort_by = (request()->has('sortBy')) ? request()->get('sortBy') : 'created_at';
                            $sort_order = (request()->has('sortOr')) ? request()->get('sortOr') : 'desc';
                            $sort_order_inverse = ($sort_order == 'asc') ? 'desc' : 'asc';
                            $sort_icon = ($sort_order == 'asc') ? 'bi-caret-down-fill' : 'bi-caret-up-fill';
                            $i = (($complaints->currentPage() - 1) * $complaints->perPage())+1;
                        @endphp
                        @foreach ($complaints as $cmp)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td><x-auth.link href="{{ url('calls/'.$cmp->id) }}" class="link-modal">{{ $cmp->code }}</x-auth.link></td>
                                <td>{{ $cmp->category->name ?? '' }}</td>
                                <td>{{ $cmp->created_at->format('d-m-Y') }}</td>
                                <td>{{ $cmp->closed_at?->format('d-m-Y') }}</td>
                                <td nowrap><x-complaint.status :status="$cmp->status"/></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item link-modal" href="{{ url('calls/'.$cmp->id) }}"><i class="bi bi-file-text"></i>&nbsp;View</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div>
                {{ $complaints->links('utils.paginator', ['modDiv' => 'nav-calls']) }}
            </div>
        @else
            <div class="alert alert-warning">
                No complaints found
            </div>
        @endif
    </div>
</div>
@include('scripts.link-modal')