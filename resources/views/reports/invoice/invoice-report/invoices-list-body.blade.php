{{-- Invoices list --}}
@php
    $sort_by = request()->get('sortBy', 'created_at');
    $sort_order = request()->get('sortOr', 'desc');
    $sort_order_inverse = ($sort_order == 'asc') ? 'desc' : 'asc';
    $sort_icon = ($sort_order == 'asc') ? 'bi-caret-down-fill' : 'bi-caret-up-fill';

    // Helper to build sort URL (cursor pagination doesn't use page numbers)
    $sortUrl = fn($column) => request()->fullUrlWithQuery([
        'sortBy' => $column,
        'sortOr' => ($sort_by === $column) ? $sort_order_inverse : 'asc',
        'cursor'  => null, // reset cursor on sort change
    ]);
@endphp

<div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
    <div class="d-flex flex-wrap align-items-center gap-1">
        <div class="input-group w-auto">
            <span class="input-group-text">Search</span>
            <input type="text" name="key" id="key" class="form-control" value="{{ request()->key }}" placeholder="Invoice Number, CRN">
        </div>
        <!-- Range Dropdown -->
        @if (request()->has('range'))
            <select name="range" id="range" class="form-select w-auto">
                <option value="">All Days Range</option>
                @foreach([
                    '0',
                    '1-15',
                    '16-30',
                    '31-60',
                    '61-90',
                    '90+'
                ] as $range)
                    <option value="{{ $range }}"
                        @selected($range == request()->range)>
                        {{ $range }} Days
                    </option>
                @endforeach
            </select>
        @endif
        <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#invoiceDateFilter" aria-expanded="false">
            <i class="bi bi-calendar3"></i>
        </button>
        <a href="{{ url('reports/invoices/list') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        {{-- No total() available with cursor pagination --}}
    </div>
</div>

<div class="collapse {{ request()->filled('date_from') || request()->filled('date_to') ? 'show' : '' }} mt-2 mb-3" id="invoiceDateFilter">
    <div class="card border-info bg-info-subtle">
        <div class="card-body py-2 px-3">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <span class="fw-semibold">Invoice Date :</span>
                <div class="input-group w-auto">
                    <span class="input-group-text">From</span>
                    <input type="text" class="form-control" name="date_from" id="date_from" value="{{ request()->date_from }}">
                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                </div>
                <div class="input-group w-auto">
                    <span class="input-group-text">To</span>
                    <input type="text" class="form-control" name="date_to" id="date_to" value="{{ request()->date_to }}">
                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover table-striped bg-white page-sort text-middle">
        <thead class="table-success align-middle">
            <tr class="bg-success-subtle">
                <th width="1%" nowrap>S No</th>
                <th nowrap>
                    <a href="{{ $sortUrl('invoice_number') }}">
                        Invoice No @if($sort_by == 'invoice_number') <i class="bi {{ $sort_icon }}"></i> @endif
                    </a>
                </th>
                <th nowrap>
                    <a href="{{ $sortUrl('invoice_date') }}">
                        Invoice Date @if($sort_by == 'invoice_date') <i class="bi {{ $sort_icon }}"></i> @endif
                    </a>
                </th>
                <th nowrap>CRN</th>
                <th nowrap>Name</th>
                <th nowrap>GA <x-master.ga-filter class="float-end" /></th>
                <th nowrap>
                    <div class="d-flex">
                        <a href="{{ $sortUrl('type_id') }}">
                            Invoice Type @if($sort_by == 'type_id') <i class="bi {{ $sort_icon }}"></i> @endif
                        </a>
                        <x-master.invoice-type-filter class="float-end" />
                    </div>
                </th>
                <th nowrap>
                    <a href="{{ $sortUrl('due_date') }}">
                        Due Date @if($sort_by == 'due_date') <i class="bi {{ $sort_icon }}"></i> @endif
                    </a>
                </th>
                <th class="text-end" nowrap>
                    <a href="{{ $sortUrl('payable_amount') }}">
                        Invoice Amount @if($sort_by == 'payable_amount') <i class="bi {{ $sort_icon }}"></i> @endif
                    </a>
                </th>
                <th class="text-end" nowrap>
                    <a href="{{ $sortUrl('balance_amount') }}">
                        Balance Amount @if($sort_by == 'balance_amount') <i class="bi {{ $sort_icon }}"></i> @endif
                    </a>
                </th>
                <th nowrap>
                    <div class="d-flex">
                        <a href="{{ $sortUrl('status_id') }}">
                            Payment Status @if($sort_by == 'status_id') <i class="bi {{ $sort_icon }}"></i> @endif
                        </a>&nbsp;
                        @php $inv_status = [1 => 'Paid', 2 => 'Not-Paid', 3 => 'Partial-Paid']; @endphp
                        <x-admin.status-filter name="status_id" :data="$inv_status" class="float-end" />
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $inv)
                <tr class="align-middle">
                    {{-- ✅ S.No removed — cursor pagination has no absolute position --}}
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><a href="{{ url('bill/invoice/' . $inv->id) }}" target="_blank">{{ $inv->invoice_number }}</a></td>
                    <td>{{ dateFormat($inv->invoice_date) }}</td>
                    <td>{{ $inv->consumer->crn ?? '' }}</td>
                    <td>{{ $inv->consumer->name ?? '' }}</td>
                    <td>{{ $inv->consumer->ga->name ?? '' }}</td>
                    <td nowrap>{{ $inv->invoiceType->name }}</td>
                    <td>{{ dateFormat($inv->due_date) }}</td>
                    <td class="text-end">{{ numberFormat($inv->payable_amount, 2) }}</td>
                    <td class="text-end">{{ numberFormat($inv->balance_amount, 2) }}</td>
                    <td><x-invoice.status :status="$inv->status"/></td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No Records Found</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="table-info">
                <th class="text-end" colspan="8">Page Total</th>
                {{-- ✅ sum() on the current page collection (not DB total) --}}
                <th class="text-end">{{ numberFormat($invoices->getCollection()->sum('payable_amount'), 2) }}</th>
                <th class="text-end">{{ numberFormat($invoices->getCollection()->sum('balance_amount'), 2) }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>

{{-- ✅ Cursor paginator renders only Prev / Next links --}}
<div class="p-1 mb-2">
    {{ $invoices->links('utils.cursor', ['modDiv' => 'invoices-report-list']) }}
</div>

@push('scripts')
    @include('scripts.datepicker', ['list' => ['date_from', 'date_to']])
@endpush