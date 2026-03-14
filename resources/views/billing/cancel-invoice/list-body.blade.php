<div>
    @if ($invoices->count() > 0)
        <div class="table-responsive" style="min-height: 500px;">
            <table class="table table-bordered table-hover">
                <thead class="table-info">
                    <tr>
                        <th width="1%" nowrap>S.No</th>
                        <th>CRN</th>
                        <th>Invoice Number</th>
                        <th>Status</th>
                        <th>Added By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = (($invoices->currentPage() - 1) * $invoices->perPage())+1;
                    @endphp
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td><x-auth.link class="link-modal" href="{{ url('consumers/'.$invoice->consumer_id) }}">{{ $invoice->consumer->crn }}</x-auth.link></td>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->status->name }}</td>
                            <td>{{ $invoice->createdBy->name }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('bill/invoice/cancelInvoice/' . $invoice->id) }}" target="_blank" action="cancel"><i class="bi bi-chevron-right"></i>&nbsp;Cancel</x-auth.link></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div>
            {{ $invoices->links('utils.paginator', ['modDiv' => 'invoice-cancel-list']) }}
        </div>
    @else
        <div class="alert alert-info">
            No invoices found
        </div>
    @endif
</div>
@include('scripts.link-modal')