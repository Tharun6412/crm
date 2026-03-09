<div class="border rounded-top">
    <div class="bg-primary-subtle p-2 fs-5 fw-semibold">
        <i class="bi bi-files"></i>&nbsp;Documents - ({{ $documents_list->count() }})
    </div>
    <div class="p-2">
        @if ($documents_list->count() > 0)
            <div class="mb-2">
                @foreach ($documents_list as $index => $doc)
                    <div class="card" style="width: 18rem;">
                        <div class="card-header">{{ $doc->docType->name }}</div>
                        <div class="card-body">
                            <img src="{{ url('master/dc_documents/'.$doc->id) }}" alt="no image found"></img>
                            <a href="{{ url('master/dc/documents/' . $doc->id) }}" target="_blank">{{ $doc->file->file_name }}</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning">
                No documents found!
            </div>
        @endif
    </div>
</div>