{{-- Create geyser connection form --}}
<div>
    <form id="geyser-form" method="POST" action="{{ url('consumers/geysers/store/'.$consumer->id) }}">
        @csrf
        <div id="consumer-error" class="mb-2"></div>
        <div id="consumer-success" class="mb-2"></div>
        <div class="card shadow-sm">
            <div class="card-header bg-secondary-subtle"><strong>Create Geyser Connection</strong></div>
            <div class="card-body">
                <x-consumer.basic-details :consumer="$consumer" class="bg-info-subtle"/>
                <div class="fs-5 fw-semibold mb-2">Geyser Connection Summary</div>
                <div class="table-responsive">
                     <table class="table table-bordered table-sm align-middle">
                         <thead class="table-light">
                             <tr>
                                 <th width="5%">S No</th>
                                 <th>Item / Description</th>
                                 <th class="text-end">Unit Price</th>
                                 <th class="text-end">Qty</th>
                                 <th class="text-end">Amount</th>
                             </tr>
                         </thead>
                         <tbody>
                             <tr>
                                 <td>1</td>
                                 <td>{{ $invoice_type->name }}</td>
                                 <td class="text-end">{{ number_format($invoice_type->basic, 2) }}</td>
                                 <td class="text-end">1</td>
                                 <td class="text-end">{{ number_format($invoice_type->basic, 2) }}</td>
                             </tr>
                             <tr>
                                 <th colspan="4" class="text-end fw-semibold">Base Amount</th>
                                 <td class="text-end">{{ number_format($invoice_type->basic, 2) }}</td>
                             </tr>
                             <tr>
                                 <th colspan="4" class="text-end fw-semibold">GST ({{ $invoice_type->tax_value }}%)</th>
                                 <td class="text-end">{{ number_format($invoice_type->price - $invoice_type->basic, 2) }}</td>
                             </tr>
                             <tr class="table-light">
                                 <th colspan="4" class="text-end fw-semibold">Total Amount</th>
                                 <th class="text-end fw-semibold">{{ number_format($invoice_type->price, 2) }}</th>
                             </tr>
                         </tbody>
                     </table>
                 </div>
                <div class="mb-2">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control" required></textarea>
                    <small class="form-text fst-italic">Maximum allowed characters: 255</small>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success "><i class="bi bi-plus-square"></i>&nbsp;Create Geyser Connection</button>
            </div>
        </div>
    </form>
</div>
@include('scripts.ajax-form-submit', ['form' => 'geyser'])