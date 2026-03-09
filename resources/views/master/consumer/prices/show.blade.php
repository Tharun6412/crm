{{-- Master price view details --}}

<div class="offcanvas-header bg-light">
    <h4 class="offcanvas-title">Price details</h4>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body">
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <td nowrap="nowrap" class="bg-light">Segment</td>
                <td>{{ $price_details->segment->name ?? '' }}</td>
            </tr>
             <tr>
                <td nowrap="nowrap" class="bg-light">GA</td>
                <td>{{ $price_details->district->ga->name ?? '' }}</td>
            </tr>
             <tr>
                <td nowrap="nowrap" class="bg-light">District</td>
                <td>{{ $price_details->district->name ?? '' }}</td>
            </tr>
             <tr>
                <td nowrap="nowrap" class="bg-light">Basic</td>
                <td>{{ $price_details->basic ?? '' }}</td>
            </tr>
             <tr>
                <td nowrap="nowrap" class="bg-light">Supply</td>
                <td>{{ $price_details->supply ?? '' }}</td>
            </tr>
             <tr>
                <td nowrap="nowrap" class="bg-light">Margin</td>
                <td>{{ $price_details->margin ?? '' }}</td>
            </tr>
             <tr>
                <td nowrap="nowrap" class="bg-light">Basic Price</td>
                <td>{{ $price_details->basic_price ?? '' }}</td>
            </tr>
             <tr>
                <td nowrap="nowrap" class="bg-light">Vat (%)</td>
                <td>{{ $price_details->tax_value ?? '' }}</td>
            </tr>
             <tr>
                <td nowrap="nowrap" class="bg-light">RSP</td>
                <td>{{ $price_details->rsp ?? '' }}</td>
            </tr>
             <tr>
                <td nowrap="nowrap" class="bg-light">Effective from</td>
                <td>{{ $price_details->effective_from?->format('d-m-Y') }}</td>
            </tr>
             <tr>
                <td nowrap="nowrap" class="bg-light">Effective To</td>
                <td>{{ $price_details->effective_to?->format('d-m-Y') }}</td>
            </tr>
             <tr>
                <td nowrap="nowrap" class="bg-light">Created By</td>
                <td>{{ $price_details->createdBy->emp_id ?? '' }}</td>
            </tr>
             <tr>
                <td nowrap="nowrap" class="bg-light">Created Date</td>
                <td>{{ $price_details->created_at?->format('d.m.Y H:i') }}</td>
            </tr>
             <tr>
                <td nowrap="nowrap" class="bg-light">Last Updated By</td>
                <td>{{ $price_details->updatedBy->emp_id ?? '' }}</td>
            </tr>
             <tr>
                <td nowrap="nowrap" class="bg-light">Last Updated  Date</td>
                <td>{{ $price_details->updated_at?->format('d.m.Y H:i') }}</td>
            </tr>               
        </table>
    </div> 
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