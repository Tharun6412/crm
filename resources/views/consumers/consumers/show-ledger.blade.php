{{-- Show Ledger details, tab content --}}

<div class="border rounded-top">
    <div class="bg-light p-2 fs-5 fw-semibold">
        <i class="bi bi-file-ruled"></i>&nbsp;Ledger
    </div>
    <div class="p-2">
        @if ($ledger_report->count() > 0)
            <div class="responsive">
                <table class="table table-bordered table-primary">
                    <thead class="table-primary">
                        <tr>
                            <th width="1%" nowrap>S.No</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Credit</th>
                            <th>Debit</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ledger_report as $ledger)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $ledger->created_at->format('d-m-Y H:m:i') }}</td>
                                <td>{{ $ledger->description }}&nbsp;</td>
                                <td class="text-end">{{ $ledger->credit }}</td>
                                <td class="text-end">{{ $ledger->debit }}</td>
                                <td class="text-end">{{ $ledger->balance }}</td>
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