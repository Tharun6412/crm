{{-- Scheme details --}}
<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">View Scheme</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body">
    <div class="table-responsive">
        <table class="table table-borderless">
            <tr>
                <td nowrap="nowrap">Scheme Name</td>
                <td>:</td>
                <td>{{ $scheme->name }}</td>
            </tr>
            <tr>
                <td nowrap="nowrap">Scheme Code</td>
                <td>:</td>
                <td>{{ $scheme->code }}</td>
            </tr>
            <tr>
                <td nowrap="nowrap">Segment</td>
                <td>:</td>
                <td>{{ $scheme->segment->name }}</td>
            </tr>
            <tr>
                <td nowrap="nowrap">Registration Charges</td>
                <td>:</td>
                <td>{{ $scheme->registration }}</td>
            </tr>
            <tr>
                <td nowrap="nowrap">Security Deposit</td>
                <td>:</td>
                <td>{{ $scheme->security }}</td>
            </tr>
            <tr>
                <td nowrap="nowrap">Consumption Deposit</td>
                <td>:</td>
                <td>{{ $scheme->consumption }}</td>
            </tr>
            <tr>
                <td nowrap="nowrap">Total Deposit</td>
                <td>:</td>
                <td>{{ $scheme->total_deposit }}</td>
            </tr>
            <tr>
                <td nowrap="nowrap">Minimum Payable Amount</td>
                <td>:</td>
                <td>{{ $scheme->min_payment }}</td>
            </tr>
            <tr>
                <td nowrap="nowrap">Emi Amount</td>
                <td>:</td>
                <td>{{ $scheme->emi_amount }}</td>
            </tr>
            <tr>
                <td nowrap="nowrap">Rental Amount</td>
                <td>:</td>
                <td>{{ $scheme->rental_amount }}</td>
            </tr>
            <tr>
                <td>Applicable GAs</td>
                <td>:</td>
                <td>{{ $scheme->gas->pluck('name')->implode(', ') }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>:</td>
                <td>@if ( $scheme->status == 1) <span class="badge bg-success">Enabled</span>
                    @else <span class="badge bg-warning">Disabled</span>
                    @endif</td>
            </tr>
            <tr>
                <td>Created Date</td>
                <td>:</td>
                <td>{{ $scheme->created_at->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td>Created By</td>
                <td>:</td>
                <td></td>
            </tr>
        </table>
    </div>
</div>