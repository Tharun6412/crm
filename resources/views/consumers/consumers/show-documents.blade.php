<div class="border rounded-top">
    <div class="bg-primary-subtle p-2 fs-5 fw-semibold">
        <i class="bi bi-files"></i>&nbsp;Documents - ({{ $documents_list->count() }})
    </div>
    <div class="p-2">
        @if ($documents_list->count() > 0)
        <div class="mb-2">
            <div class="d-flex flex-wrap gap-3 p-2">
                @foreach ($documents_list as $status_id => $documents)
                    <div class="row mt-3">
                        <h5 class="mb-3">{{ $documents->first()->status->name ?? "Additional Documents" }}</h5>
                        <div class="d-flex flex-wrap gap-3 mb-4">
                            @foreach ($documents as $doc)
                                <div class="card border-info shadow-sm" style="width: 13rem;">
                                    
                                    <div class="card-header bg-info-subtle text-dark">
                                        {{ $doc->docType->name ?? 'Not Specified' }}
                                    </div>

                                    <div class="card-body text-center position-relative">
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
                                        <a href="{{ url('master/dc/documents/' . $doc->file->id) }}" title="{{ $doc->file->file_name }} | added date: {{ $doc->created_at?->format('d-m-Y H:i:s') }}" target="_blank" class="fs-1">{!! $icons[$ext] ?? '<i class="bi bi-file-image"></i>' !!}</a>
                                        <div class="position-absolute bottom-0 end-0">
                                            <span class="text-secondary p-1" style="font-size: 9px;"><i class="bi bi-calendar3"></i> {{ $doc->created_at?->format('d-m-Y H:i:s') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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