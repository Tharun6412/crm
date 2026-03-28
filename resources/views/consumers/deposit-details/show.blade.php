<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Pay Security Deposit</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Consumer basic details component --}}
            <x-consumer.basic-details :consumer="$sd_payment->consumer" type="2" class="bg-info-subtle shadow-sm" />
            <div class="mt-3">
                {{-- SD Payment details --}}
                <div class="row g-2 pb-2 mb-2">
                    <div class="fw-semibold bg-secondary-subtle p-2">SD Transaction Details</div>
                    <div class="col-sm-3 text-end fw-semibold">Paid Amount : </div>
                    <div class="col-sm-3">{{ $sd_payment->amount }}</div>
                    <div class="col-sm-3 text-end fw-semibold">Balance : </div>
                    <div class="col-sm-3">{{ $sd_payment->balance }}</div>
                    <div class="col-sm-3 text-end fw-semibold text-nowrap">Transaction Number : </div>
                    <div class="col-sm-3">{{ $sd_payment->transaction_number }}</div>
                    <div class="col-sm-3 text-end fw-semibold">EMI No : </div>
                    <div class="col-sm-3">{{ $sd_payment->emi_no ?? '' }}</div>
                    <div class="col-sm-3 text-end fw-semibold">Payment Type : </div>
                    <div class="col-sm-3">{{ $sd_payment->paymentType->name }}</div>
                    <div class="col-sm-3 text-end fw-semibold">Status : </div>
                    <div class="col-sm-3"><x-payments.status :status="$sd_payment->status" /></div>
                    <div class="col-sm-3 text-end fw-semibold">Transaction Date : </div>
                    <div class="col-sm-3">{{ $sd_payment->created_at->format('d-m-Y') }}</div>
                    <div class="col-sm-3 text-end fw-semibold">Added By : </div>
                    <div class="col-sm-3">{{ $sd_payment->createdBy->name ?? '' }}</div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
