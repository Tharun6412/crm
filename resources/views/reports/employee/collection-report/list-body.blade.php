<div>
    <span class="mb-2"><h5>({{ $employee_collection->count() }}) &nbsp;Records Found.</h5></span>
    <table class="table table-bordered table-striped" id="employee-report">
        <thead class="table-success">
            <tr>
                <th width="1%">S.No</th>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th class="text-end">Invoice Amount&nbsp;(&#8377;)</th>
                <th class="text-end">SD Amount&nbsp;(&#8377;)</th>
                <th class="text-end">Total Amount&nbsp;(&#8377;)</th>
            </tr>
        </thead>
        <tbody>
            @if($employee_collection->count() > 0)
                @php
                    $inv_amt = $sd_amt = $tot_amt = 0; 
                @endphp
                @foreach ($employee_collection as $emp)
                    @php
                        $inv_amt += $emp->invoice_collection;
                        $sd_amt += $emp->sd_collection;
                        $tot_amt += $inv_amt + $sd_amt;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $emp->emp_id }}</td>
                        <td>{{ $emp->name }}</td>
                        <td class="text-end">{{ numberFormat($emp->invoice_collection, 2) }}</td>
                        <td class="text-end">{{ numberFormat($emp->sd_collection, 2) }}</td>
                        <td class="text-end">
                            <a class="link-modal" href="{{ url('reports/employee/collection/details?emp_id=' . $emp->id . '&ga_id=' . $ga_id . '&date_from=' . $date_from . '&date_to=' . $date_to) }}">
                                {{ numberFormat(($emp->invoice_collection + $emp->sd_collection), 2) }}
                            </a>
                        </td>
                    </tr>
                @endforeach
                <tr class="bg-info-subtle fw-semibold">
                    <td colspan="3" class="text-end">Totals</td>
                    <td class="text-end">{{ numberFormat($inv_amt, 2) }}</td>
                    <td class="text-end">{{ numberFormat($sd_amt, 2) }}</td>
                    <td class="text-end">{{ numberFormat($tot_amt, 2) }}</td>
                </tr>
            @else
                <tr class="bg-white text-center fw-semibold">
                    <td colspan="7">No records found</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@include('scripts.export-table', [
    'table' => 'employee-report',
    'button' => 'exportBtn',
    'tabBased' => false,
    'filename' => 'employee_report',
    'sheet'    => 'Report',
])
@include('scripts.link-modal')