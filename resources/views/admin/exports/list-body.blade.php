{{-- User Export list body --}}
{{-- Search form --}}
<form id="users-search-form" action="{{ url('admin/users') }}" method="GET">
    <div class="d-flex justify-content-between">
        <div class="row g-2 align-items-center pb-2">
            <div class="col-auto">
                <span class="fw-bold">({{ $exports->count() }})</span> Records found
            </div>
        </div>
    </div>
{{-- Display --}}
@if ($exports->count() > 0)
    <div class="table-responsive" style="min-height: 500px;">
        <table class="table table-bordered table-striped bg-white page-sort">
            <thead class="table-secondary">
                <tr class="bg-light">
                    <th width="1%" nowrap>S No</th>
                    <th nowrap>File Name</th>
                    <th>Created Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </thead>
                </tr>
            <tbody>
                @foreach ($exports as $export)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $export->file_name }}</td>
                        <td>{{ $export->created_at->format('d-m-Y H:i') }}</td>
                        <td>{{ $export->status == 0 ? "Pending" : "Completed" }}</td>
                        <td>
                            @if ($export->status == 1)
                                <a href="{{ asset('storage/'.$export->file_name) }}" title="Download Export" class="btn btn-sm btn-primary"><i class="bi bi-download"></i>&nbsp;Download</a>
                            @endif
                            <a href="{{ url('user/exports/'.$export->id) }}" title="Delete Export" class="btn btn-sm btn-danger ajax-link-delete"><i class="bi bi-trash"></i>&nbsp;Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <x-layouts.callout-info message="No exports found."/>
@endif
</form>
{{-- Dynamic scripts --}}
@include('scripts.ajax-form-search', ['form' => 'exports'])
@include('scripts.ajax-link', ['div' => 'exports-list'])
@include('scripts.link-modal')
@include('scripts.ajax-link-delete')