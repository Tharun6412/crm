{{-- Scheme details --}}
<div class="offcanvas-header border-bottom">
    <h4 class="offcanvas-title" id="offcanvasRightLabel">Scheme - {{ $scheme->code }}</h4>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body">
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <td nowrap="nowrap" class="bg-light">Scheme Name</td>
                <td>{{ $scheme->name }}</td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="bg-light">Scheme Code</td>
                <td>{{ $scheme->code }}</td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="bg-light">Segment</td>
                <td><span class="badge text-bg-secondary">{{ $scheme->segment->name }}</span></td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="bg-light">Connection Type</td>
                <td><span class="badge text-bg-secondary">{{ $scheme->connectionType->name ?? '' }}</span></td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="bg-light">Registration Charges</td>
                <td>{{ numberFormat($scheme->registration) }}</td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="bg-light">Security Deposit</td>
                <td>{{ numberFormat($scheme->security) }}</td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="bg-light">Consumption Deposit</td>
                <td>{{ numberFormat($scheme->consumption) }}</td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="bg-light">Total Deposit</td>
                <td>{{ numberFormat($scheme->total_deposit) }}</td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="bg-light">Minimum Payable Amount</td>
                <td>{{ numberFormat($scheme->min_payment) }}</td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="bg-light">Emi Amount</td>
                <td>{{ numberFormat($scheme->emi_amount) }}</td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="bg-light">Rental Amount</td>
                <td>{{ numberFormat($scheme->rental_amount) }}</td>
            </tr>
            <tr>
                <td class="bg-light">Applicable GAs</td>
                <td>{{ $scheme->gas->pluck('name')->implode(', ') }}</td>
            </tr>
            <tr>
                <td class="bg-light">Status</td>
                <td>@if ( $scheme->status == 1) <span class="badge bg-success">Enabled</span>
                    @else <span class="badge bg-warning">Disabled</span>
                    @endif</td>
            </tr>
            <tr>
                <td class="bg-light">Created Date</td>
                <td>{{ $scheme->created_at->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td class="bg-light">Created By</td>
                <td>{{ $scheme->createdBy->name }}</td>
            </tr>
        </table>
    </div>
</div>