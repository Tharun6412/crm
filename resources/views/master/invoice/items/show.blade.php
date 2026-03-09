{{-- Master invoice items view details --}}

<div class="offcanvas-header bg-light">
    <h4 class="offcanvas-title">Item details #{{ $item->code }}</h4>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body">
    <div class="table-responsive">
        <table class="table">
            <tbody>
                <tr>
                    <td class="bg-light">Type</td>
                    <td>{{ $item->type->name ?? '' }}</td>
                </tr>
                <tr>
                    <td class="bg-light">Code</td>
                    <td>{{ $item->code }}</td>
                </tr>
                <tr>
                    <td class="bg-light">Name</td>
                    <td>{{ $item->name }}</td>
                </tr>
                <tr>
                    <td class="bg-light">HSN</td>
                    <td>{{ $item->hsn }}</td>
                </tr>
                <tr>
                    <td class="bg-light">Basic</td>
                    <td>{{ numberFormat($item->basic, 2) }}</td>
                </tr>
                <tr>
                    <td class="bg-light">Tax</td>
                    <td>{{ numberFormat($item->tax_value, 2) }}%</td>
                </tr>
                <tr>
                    <td class="bg-light">Price</td>
                    <td>{{ numberFormat($item->price, 2) }}</td>
                </tr>
                <tr>
                    <td class="bg-light">Created By</td>
                    <td>{{ $item->createdBy->emp_id ?? '' }}</td>
                </tr>
                <tr>
                    <td class="bg-light">Created Date</td>
                    <td>{{ $item->created_at?->format('d.m.Y H:i') }}</td>
                </tr>
                <tr>
                    <td class="bg-light">Last Updated By</td>
                    <td>{{ $item->updatedBy->emp_id ?? '' }}</td>
                </tr>
                <tr>
                    <td class="bg-light">Last Updated Date</td>
                    <td>{{ $item->updated_at?->format('d.m.Y H:i') }}</td>
                </tr>   
            </tbody>
        </table>
    </div>
</div>