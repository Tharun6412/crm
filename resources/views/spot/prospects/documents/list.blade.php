<?php
/**
 * Prospect documents list
 */
?>
<div class="bd-callout bd-callout-primary bg-transparent card mt-0 border-primary mb-3">
    <div class="clearfix mb-2">
        <h4 class="float-start">Documents</h4>
        <div class="float-end">
            <a class="btn btn-sm btn-success ajax-link" href="{{ url('spot/prospectDocument/create/'.$prospect->id.'?type=8') }}"><i class="bi bi-file-earmark-plus"></i>&nbsp;Add Document</a>
        </div>
    </div>
    @php
        $i = 1;
    @endphp
    <a class="visually-hidden" href="{{ url('spot/prospects/'.$prospect->id.'?reload=true&type=8') }}" data-custom-attr="value" id="reload-form">Hidden Link</a>
    @if (isset($prospect_documents) and !empty($prospect_documents))
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
                    @foreach ($prospect_documents as $key => $document)
                        <tr>
                            <td class="text-center">{{ $i++; }}</td>
                            <td class="text-center">
                                <a href="{{ url('dc/documents/'.$document->doc_file_id) }}" title="{{ $document->file->file_name_original }}" target="_blank">{{ $document->file->doc_number }}</a>
                            </td>
                            <td>{{ $document->documentType->name }}&nbsp;{{ $document->offer_count }}</td>
                            <td>
                                {{ ($document->status == 1 and $document->win == 1) ? "win" : (($document->status == "1") ? "Approved" : ($document->status == "2" ? "Rejected" : '--')) }}
                            </td>
                            <td>{{ $document?->created_at }}</td>
                            <td>{{ "1" }}</td>
                            <td nowrap>EDIT</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class='alert alert-warning mb-0'>No records found!</div>
    @endif
</div>
