{{-- Show Ledger details, tab content --}}

<div class="border rounded-top">
    <div class="bg-primary-subtle p-2 fs-5 fw-semibold">
        <i class="bi bi-file-ruled"></i>&nbsp;Ledger Report
    </div>
    <div class="text-end p-2 pb-0">
        <button type="button" id="exportBtn" class="btn btn-outline-info brn-sm mb-0"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</button>
    </div>
    <div class="p-2">
        @if ($ledger_report->count() > 0)
            <div class="responsive">
                <table class="table table-bordered table-striped" id="ledger-report">
                    <thead class="table-primary">
                        <tr>
                            <th width="1%" nowrap>S.No</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th class="text-end">Debit(Dr)</th>
                            <th class="text-end">Credit(Cr)</th>
                            <th class="text-end">Balance(&#8377;)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $balance = 0;
                        @endphp
                        @foreach ($ledger_report as $ledger)
                            @php
                                if($ledger->type == "Debit") {
                                    $balance += $ledger->payable_amount;
                                }else {
                                    $balance -= $ledger->payable_amount;
                                }
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $ledger->created_at?->format('d-m-Y H:i') }}</td>
                                @if ($ledger->type == "Debit")
                                    <td>{{ $ledger->invoiceType?->name }}&nbsp;<a href="{{ url('bill/invoice/'.$ledger->inv_id) }}" target="_blank"><span class="text-danger">{{ $ledger->invoice_number }}</span></a></td>
                                    <td class="text-end"><span class="text-danger">{{ $ledger->payable_amount }}</span></td>
                                    <td></td>
                                    <td class="text-end">{{ numberFormat($balance, 2) }}</td>
                                @else
                                    <td>Payment against invoice&nbsp;<a href="{{ url('bill/invoice/' . $ledger->inv_id) }}" target="_blank"><span class="text-success">{{ $ledger->invoice_number }}</span></a></td>
                                    <td></td>
                                    <td class="text-end"><span class="text-success">-{{ $ledger->payable_amount }}</span></td>
                                    <td class="text-end">{{ numberFormat($balance, 2) }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-warning">
                No records found
            </div>
        @endif
    </div>
</div>
@include('scripts.export-table', [
    'table' => 'ledger-report',
    'button' => 'exportBtn',
    'tabBased' => false,
    'filename' => 'ledger-report',
    'sheet'    => 'Report',
])