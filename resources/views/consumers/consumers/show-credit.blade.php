{{-- Show Credit / Debit note details, tab content --}}

<div class="border rounded-top">
    <div class="bg-light p-2 fs-5 fw-semibold">
        <i class="bi bi-file-diff"></i>&nbsp;Credit / Debit Notes&nbsp;-&nbsp;({{ $credit_notes->count() }})
    </div>
    <div class="p-2">
        @if ($credit_notes->count() > 0)
            <table class="table table-bordered table-hover table-primary">
                <thead class="table-primary">
                    <tr>
                        <th width="1%" nowrap>S No</th>
                        <th>Type</th>
                        <th>Code</th>
                        <th>Date</th>
                        <th class="text-end">Amount</th>
                        <th>Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($credit_notes as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ ($item->type == 1) ? 'Credit' : 'Debit' }} Note</td>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->created_at?->format('d-m-Y H:i') }}</td>
                            <td class="text-end">{{ numberFormat($item->total_amount, 2) }}</td>
                            <td>{{ $item->createdBy->emp_id }}</td>
                            <td>
                                <a type="button" class="btn btn-primary btn-sm" href="{{ url('bill/creditNote/' . $item->id) }}" target="_blank">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-warning">
                No credit / debit note data found!
            </div>
        @endif
    </div>
</div>