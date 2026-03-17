{{-- Invoice address details --}}
<div class="offcanvas-header border-bottom">
    <h4 class="offcanvas-title" id="offcanvasRightLabel">Invoice Address Details</h4>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td class="bg-light">GA</td>
                <td>{{ $address->ga->name ?? '' }}</td>
            </tr>
            <tr>
                <td class="bg-light">Address</td>
                <td>
                    {{ $address->line1 }},<br>
                    {{ $address->line2 }},<br>
                    {{ $address->city }}, {{ $address->district->name ?? '' }},<br>
                    {{ $address->state->name ?? '' }} - {{ $address->pincode }}.
                </td>
            </tr>
            <tr>
                <td class="bg-light">Created At</td>
                <td>{{ $address->created_at?->format('d-m-Y H:i') }}</td>
            </tr>
        </tbody>
    </table>
</div>