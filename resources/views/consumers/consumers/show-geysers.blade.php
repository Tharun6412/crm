<div class="border rounded-top">
    <div class="bg-primary-subtle p-2 fs-5 fw-semibold">
        <i class="bi bi-moisture"></i>&nbsp;Geyser Connections&nbsp;-&nbsp;({{ $geysers->count() }})
    </div>
    <div class="p-2">
        @if ($geysers->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-primary">
                    <thead class="table-primary">
                        <tr>
                            <th width="1%" nowrap>S.No</th>
                            <th>Geyser Code</th>
                            <th>Invoice No</th>
                            <th>Amount</th>
                            <th>Balance</th>
                            <th>Invoice Status</th>
                            <th>Geyser Status</th>
                            <th>Created Date</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($geysers as $geyser)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td ><a href="{{ url('consumers/geysers/' . $geyser->id) }}" class="link-modal">{{ $geyser->code ?? '' }}</a></td>
                                <td><a href="{{ url('bill/invoice/'.$geyser->invoice_id) }}" target="_blank">{{ $geyser->invoice->invoice_number }}</a></td>
                                <td>{{ numberFormat($geyser->invoice->payable_amount ?? 0,2 )}}</td>
                                <td>{{ numberFormat($geyser->invoice->balance_amount ?? 0,2 )}}</td>
                                <td><x-invoice.status :status="$geyser->invoice->status"/></td>
                                <td><x-geyser.status-change :status="$geyser->status"/></td>
                                <td> {{ dateFormat($geyser->created_at) }}</td>
                                <td>{{ $geyser->createdBy->name ?? ''}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-warning">
                No complaints found
            </div>
        @endif
    </div>
</div>
@include('scripts.link-modal')