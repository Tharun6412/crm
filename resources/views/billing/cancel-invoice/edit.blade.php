<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Cancel Invoice&nbsp; <span class="text-warning-emphasis">{{ $invoice->invoice_number }}</span></h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <x-consumer.invoice-details :invoice="$invoice" class="bg-info-subtle mt-1"/>
            <div id="cancel-success">
                <form id="cancel-form" action="{{ url('bill/invoice/cancelInvoiceUpdate/'.$invoice->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2 mt-3">
                        <label class="col-sm-3 col-form-label text-end">Notes&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                            <span class="text-danger validate-err-msg" id="notes-error"></span>
                        </div>
                    </div>
                    <div class="m-1" id="cancel-error"></div>
                    <div class="row">
                        <div class="offset-sm-3 col-sm-8">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-x-lg" aria-hidden="true">&nbsp;</i>Cancel Invoice
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-file-submit', ['form' => 'cancel'])
