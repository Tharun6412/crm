{{-- Master invoice items view details --}}

<div class="offcanvas-header bg-light">
    <h4 class="offcanvas-title">Item details #{{ $item->code }}</h4>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body">
    <div>Type: {{ $item->type->name ?? '' }}</div>
    <div>Code: {{ $item->code }}</div>
    <div>Name: {{ $item->name }}</div>
    <div>HSN: {{ $item->hsn }}</div>
    <div>Basic: {{ numberFormat($item->basic, 2) }}</div>
    <div>Tax: {{ numberFormat($item->tax_value, 2) }}%</div>
    <div>Price: {{ numberFormat($item->price, 2) }}</div>
    <div>Created By: {{ $item->createdBy->emp_id ?? '' }}</div>
    <div>Created Date: {{ $item->created_at?->format('d.m.Y H:i') }}</div>
    <div>Last Updated By: {{ $item->updatedBy->emp_id ?? '' }}</div>
    <div>Last Updated  Date: {{ $item->updated_at?->format('d.m.Y H:i') }}</div>
</div>