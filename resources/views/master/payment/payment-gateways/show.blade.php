{{-- Payment gateway details --}}
<div class="offcanvas-header border-bottom">
    <h4 class="offcanvas-title" id="offcanvasRightLabel">Gateway Details</h4>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body">
<table class="table table-bordered mb-3">
    <tr>
        <td class="table-light">Gateway</td>
        <td>{{ $gateway->gateway }}</td>
    </tr>
    <tr>
        <td class="table-light">Mode</td>
        <td>{{ $gateway->mode }}</td>
    </tr>
    <tr>
        <td class="table-light">Status</td>
        <td>{{ $gateway->is_active }}</td>
    </tr>
</table>  
    @if ($gateway->details->count() > 0)
        <table class="table table-bordered">
            <thead class="table-primary">
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