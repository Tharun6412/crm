<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Cancel Invoice&nbsp;#{{ $invoice->invoice_number }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="cancel-success">
                <form id="cancel-form" action="{{ url('bill/invoice/cancelInvoiceUpdate/'.$invoice->id) }}">
                    @csrf
                    @method('PUT')
                    <x-consumer.invoice-details :invoice="$invoice"/>
                    <div class="row mb-2">
                        <label class="col-form-label">Notes&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-12">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                            <span class="text-danger validate-err-msg" id="notes-error"></span>
                        </div>
                    </div>
                    <div class="mb-3" id="cancel-error"></div>
                    <div class="row mb-2">
                        <div class="col-md-12 col-sm-12">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check2-square" aria-hidden="true">&nbsp;</i>Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-file-submit', ['form' => 'cancel'])
