{{-- Show Ledger details, tab content --}}

<div class="border rounded-top">
    <div class="bg-primary-subtle p-2 fs-5 fw-semibold">
        <i class="bi bi-file-ruled"></i>&nbsp;Ledger
    </div>
    <div class="p-2">
        @if ($ledger_report->count() > 0)
            <div class="responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th width="1%" nowrap>S.No</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th class="text-end">Credit(Cr)</th>
                            <th class="text-end">Debit(Dr)</th>
                            <th class="text-end">Balance(&#8377;)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ledger_report as $ledger)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $ledger->created_at->format('d-m-Y H:i') }}</td>
                                <td>{{ $ledger->description }}&nbsp;{{ $ledger->legible?->inv_number }}&nbsp;</td>
                                <td class="text-end text-danger">{{ ($ledger->credit > 0) ? '-' . numberFormat($ledger->credit, 2) : '' }}</td>
                                <td class="text-end text-success">{{ ($ledger->debit > 0) ? '+' . numberFormat($ledger->debit, 2) : '' }}</td>
                                <td class="text-end">{{ $ledger->balance }}</td>
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