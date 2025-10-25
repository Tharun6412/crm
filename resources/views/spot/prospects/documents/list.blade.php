{{-- Prospects Documents List --}}
<div>
    @if (session('doc_success'))
        <div class="alert alert-success alert-dismissible fade show">
            <strong><i class="bi bi-check2-circle"></i>&nbsp;Success</strong>&nbsp;{{ session('doc_success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
<div class="bd-callout bd-callout-primary bg-transparent card mt-0 border-primary mb-3">
    <div class="clearfix mb-2">
        <h4 class="float-start">Documents</h4>
        <div class="float-end">
            <a class="btn btn-sm btn-success ajax-link" href="{{ url('spot/prospectDocument/create/'.$prospect->id.'?type=2') }}"><i class="bi bi-file-earmark-plus"></i>&nbsp;Add Document</a>
        </div>
    </div>
    @php
        $i = 1;
    @endphp
    <a class="visually-hidden" href="{{ url('spot/prospects/'.$prospect->id.'?reload=true&type=2') }}" data-custom-attr="value" id="reload-form">Hidden Link</a>
    @if ($prospect->documents->count() > 0)
        <div class="table-responsive spot-table">
            <table class="table table-bordered table-hover table-striped table-sm align-middle mb-0">
                <thead>
                    <tr class="spot-table-bg">
                        <th class="text-center">S.No.</th>
                        <!-- <th nowrap class="text-center">Document</th> -->
                        <th nowrap>Document</th>
                        <th nowrap>Document Type</th>
                        <th>Status</th>
                        <th nowrap>Create date</th>
                        <th nowrap>Created By</th>
                        <th nowrap>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($prospect->documents as $key => $document)
                        <tr>
                            <td class="text-center">{{ $i++; }}</td>
                            <td class="text-center">
                                <a href="{{ url('dc/documents/'.$document->doc_file_id) }}" title="{{ $document->file->file_name_original }}" target="_blank"><i class="bi bi-file-earmark-pdf fs-5 text-danger"></i></a>
                            </td>
                            <td>{{ $document->documentType->name }}&nbsp;{{ $document->offer_count }}</td>
                            <td>
                                {{ ($document->status == 1 and $document->win == 1) ? "win" : (($document->status == "1") ? "Approved" : ($document->status == "2" ? "Rejected" : '--')) }}
                            </td>
                            <td>{{ $document?->created_at }}</td>
                            <td>{{ $document->createdBy->first_name }}</td>
                            <td nowrap="">
                                <a href="{{ url('dc/documents/'.$document->doc_file_id) }}" title="{{ $document->file->file_name_original }}" class="btn btn-sm btn-outline-primary" target="_blank"><i class="bi bi-file-earmark-pdf"></i></a>
                                <a class="btn btn-sm btn-outline-danger ajax-link-file-delete" href="{{ url('spot/prospectDocument/'.$document->id) }}" title="Delete document"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class='alert alert-warning mb-0'>No records found!</div>
    @endif
</div>
@include('scripts.ajax-link', ['div' => 'action-type'])
@include('scripts.ajax-link-file-delete', ['callback' => 'reloadDocForm()'])
<script type="text/javascript">
    function reloadDocForm()
    {
        $.get($('#reload-form').attr('href'), function(data) {
            $('#prospect-documents').html(data);
        });
    }
</script>
