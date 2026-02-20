{{-- GA Head Approval --}}
<div class="card bd-callout bd-callout-success bg-white mt-0 border-success mb-3" id="ga-approve-success">
    <form id="ga-approve-form" method="POST" action="{{ url('spot/prospectStatus/gaHeadSubmit/'.$id) }}">
        @csrf
        <h4 class="modal-title">Approve Offer</h4>
        <div class="row">
            <label class="col-form-label col-sm-4 text-end">Document&nbsp;<span class="error text-danger">*</span>&nbsp;:</label>
            <div class="col-md-6">
                <select class="form-select form-select-sm" name="offer_document" id="offer_document">
                    <option value="">All</option>
                    @foreach ($offer_type_docs as $doc_val)
                        <option value="{{ $doc_val->id }}">Offer&nbsp;-&nbsp;{{ $doc_val->offer_count }}&nbsp;({{ $doc_val->file->file_name }})</option>
                    @endforeach
                </select>
                <small class="text-danger" id="offer_document-error"></small>
            </div>
        </div>
        <div class="row mb-2">
            <label class="col-form-label col-sm-4 text-end">Approval Note&nbsp;<span class="error text-danger">*</span>&nbsp;:</label>
            <div class="col-md-6">
                <textarea name="notes" id="notes" class="form-control form-control-sm" placeholder="Enter Note"></textarea>
                <small class="text-danger" id="notes-error"></small>
            </div>
        </div>
        <div class="row mb-2">
            <label class="col-form-label col-sm-4 text-end">Approval Document&nbsp;:</label>
            <div class="col-md-6">
                <input type="radio" name="approval_status" id="approval_status_1" value="1"/>Accepted
                <input type="radio" name="approval_status" id="approval_status_2" value="2"/>Rejected
                <br/><small class="text-danger" id="approval_status-error"></small>
            </div>
        </div>
        <div class="row">
            <div class="offset-md-4 col-md-6">
                <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-square"></i>&nbsp;Update</button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="$('#action-type').html('')"><i class="bi bi-x-lg"></i>&nbsp;Close</button>
            </div>
        </div>
    </form>
</div>
@include('scripts.ajax-file-submit', ['form' => 'ga-approve', 'callback' => 'reloadStatusHistory()'])

