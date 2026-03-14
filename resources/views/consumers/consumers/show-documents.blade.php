<div class="border rounded-top">
    <div class="bg-primary-subtle p-2 fs-5 fw-semibold">
        <i class="bi bi-files"></i>&nbsp;Documents - ({{ $documents_list->count() }})
    </div>
    <div class="p-2">
        @if ($documents_list->count() > 0)
        <div class="mb-2">
            <div class="d-flex flex-wrap gap-3 p-2">
                @foreach ($documents_list as $index => $doc)
                    <div class="card border-info shadow-sm" style="width: 13rem;">
                        <div class="card-header bg-info-subtle text-dark">
                            {{ $doc->docType->name }}
                        </div>
                        <div class="card-body text-center">
                           @php
                            $ext = strtolower(pathinfo($doc->file->file_name, PATHINFO_EXTENSION));
                            $icons = [
                                'pdf' => '<i class="bi bi-file-earmark-pdf"></i>',
                                'doc' => '<i class="bi bi-file-earmark-word"></i>',
                                'docx' => '<i class="bi bi-file-earmark-word"></i>',
                                'xls' => '<i class="bi bi-file-spreadsheet"></i>',
                                'xlsx' => '<i class="bi bi-file-spreadsheet"></i>',
                                'jpg' => '<i class="bi bi-file-image"></i>',
                                'jpeg' => '<i class="bi bi-file-image"></i>',
                                'png' => '<i class="bi bi-file-image"></i>',
                            ];
                            @endphp                            
                            {{-- <img src="/icons/{{ $icons[$ext] ?? 'file.png' }}" width="20">
                            {{ $doc->file->file_name }} --}}
                            <a href="{{ url('master/dc/documents/' . $doc->id) }}" title="{{ $doc->file->file_name }}" target="_blank" class="fs-1">{!! $icons[$ext] ?? '<i class="bi bi-file-image"></i>' !!}</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>    
        @else
            <div class="alert alert-warning">
                No documents found!
            </div>
        @endif
    </div>
</div>