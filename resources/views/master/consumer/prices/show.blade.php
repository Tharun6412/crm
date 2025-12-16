{{-- Master price view details --}}

<div class="offcanvas-header bg-light">
    <h4 class="offcanvas-title">Price details</h4>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body">
    <div>Segment: {{ $price_details->segment->name ?? '' }}</div>
    <div>GA: {{ $price_details->district->ga->name ?? '' }}</div>
    <div>District: {{ $price_details->district->name ?? '' }}</div>
    <div>Basic: {{ $price_details->basic ?? '' }}</div>
    <div>Supply: {{ $price_details->supply ?? '' }}</div>
    <div>Margin: {{ $price_details->margin ?? '' }}</div>
    <div>Basic Price: {{ $price_details->basic_price ?? '' }}</div>
    <div>Vat (%): {{ $price_details->tax_value ?? '' }}</div>
    <div>RSP: {{ $price_details->rsp ?? '' }}</div>
    <div>Effective from: {{ $price_details->effective_from?->format('d-m-Y') }}</div>
    <div>Effective To: {{ $price_details->effective_to?->format('d-m-Y') }}</div>
    <div>Created By: {{ $price_details->createdBy->emp_id ?? '' }}</div>
    <div>Created Date: {{ $price_details->created_at?->format('d.m.Y H:i') }}</div>
    <div>Last Updated By: {{ $price_details->updatedBy->emp_id ?? '' }}</div>
    <div>Last Updated  Date: {{ $price_details->updated_at?->format('d.m.Y H:i') }}</div>
    @if ($price_details->history->count() > 0)
        <div class="fs-5 fw-semibold mt-2">History:</div>
        <div class="table-responsive">
            <table class="table table-bordered table-info">
                <thead class="table-info">
                    <tr>
                        <th width="1%" nowrap>S.No</th>
                        <th>Basic</th>
                        <th>RSP</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($price_details->history as $price_history)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-end" nowrap>{{ $price_history->basic_price }}</td>
                            <td class="text-end" nowrap>{{ $price_history->rsp }}</td>
                            <td nowrap>{{ $price_history->effective_from?->format('d-m-Y') }}</td>
                            <td nowrap>{{ $price_history->effective_to?->format('d-m-Y') }}</td>
                            <td nowrap>{{ $price_history->updated_at?->format('d-m-Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>