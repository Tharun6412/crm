{{-- Documents list body view --}}
<form action="{{ url('master/dc/documents') }}" id="dc-search-form">
    <div class="row g-1 mb-3">
        <div class="col-auto">
            <div class="input-group">
                <input type="text" name="search_key" id="search_key" class="form-control form-control-sm" placeholder="Search here..." value="{{ request()->search_key }}">
                <button class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
            </div>
        </div>
        <div class="col-auto">
            <a href="{{ url('master/dc/documents') }}" class="btn btn-warning btn-sm ajax-link"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto">
            ({{ $documents->total() }}) Records
        </div>
        <div class="col">
            <a href="{{ url('master/dc/documents/create') }}" class="btn btn-success btn-sm float-end link-modal"><i class="bi bi-file-plus"></i>&nbsp;Upload</a>
        </div>
    </div>

    {{-- Display --}}
    @if ($documents->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="bg-light">
                        <th width="1%" nowrap>S No</th>
                        <th>Number</th>
                        <th>Type</th>
                        <th>Tag</th>
                        <th>Description</th>
                        <th>Name</th>
                        <th>Added By</th>
                        <th>Added Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sno = 1;
                    @endphp
                    @foreach ($documents as $document)
                        <tr>
                            <td>{{ $sno++ }}</td>
                            <td>{{ $document->doc_number }}</td>
                            <td>
                                @isset($document->type->name)
                                    {{ $document->type->name }}
                                @endisset
                            </td>
                            <td>{{ $document->tag }}</td>
                            <td>{{ $document->description }}</td>
                            <td title="{{ $document->file_path ?? '-no-' }}"><i class="bi bi-file-earmark-check"></i>&nbsp;{{ $document->file_name }}</td>
                            <td>
                                @isset($document->createdBy->first_name)
                                    {{ $document->createdBy->name }}
                                @endisset
                            </td>
                            <td>{{ $document->created_at->format('d.m.Y') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item disabled" href="#"><i class="bi bi-lightning"></i>&nbsp;Action</a></li>
                                        <li><a class="dropdown-item" href="{{ url('master/dc/documents/' . $document->id) }}" target="_blank"><i class="bi bi-cloud-download"></i>&nbsp;Download</a></li>
                                        @if (isSuperAdmin() OR isAdmin())
                                            <li><a class="dropdown-item ajax-link-delete" href="{{ url('master/dc/documents/' . $document->id) }}"><i class="bi bi-trash"></i>&nbsp;Delete</a></li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $documents->links('utils.paginator', ['modDiv' => 'dc-list']) }}
    @else
        <x-layouts.callout-info message="No documents found!"/>
    @endif
</form>
{{-- Dynamic scripts --}}
@include('scripts.ajax-form-search', ['form' => 'dc'])
@include('scripts.ajax-link', ['div' => 'dc-list'])
@include('scripts.link-modal')
@include('scripts.ajax-link-delete')