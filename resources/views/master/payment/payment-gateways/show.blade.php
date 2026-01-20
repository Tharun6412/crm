{{-- Payment gateway details --}}
<div class="offcanvas-header border-bottom">
    <h4 class="offcanvas-title" id="offcanvasRightLabel">Gateway Details</h4>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body">
    <div class="row g-2 mb-2">
        <div class="col-sm-6 text-end fw-semibold">Gateway:</div>
        <div class="col-sm-6">{{ $gateway->gateway }}</div>
        <div class="col-sm-6 text-end fw-semibold">Mode:</div>
        <div class="col-sm-6">{{ $gateway->mode }}</div>
        <div class="col-sm-6 text-end fw-semibold">Status:</div>
        <div class="col-sm-6">{{ $gateway->is_active }}</div>
    </div>
    @if ($gateway->details->count() > 0)
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>GA</th>
                    <th>Sub-Merchant ID</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gateway->details as $item)
                    <tr>
                        <td>{{ $item->ga->name }}</td>
                        <td>{{ $item->sub_merchant_id }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>