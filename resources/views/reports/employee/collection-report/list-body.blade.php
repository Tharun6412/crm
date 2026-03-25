<div>
    <span class="mb-2"><h5>({{ $result->total() }}) &nbsp;Records Found.</h5></span>
    <table class="table table-bordered table-striped" id="employee-report">
        <thead class="table-success">
            <tr>
                <th width="1%">S.No</th>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>GA Name</th>
                <th class="text-end">Invoice Amount&nbsp;(&#8377;)</th>
                <th class="text-end">SD Amount&nbsp;(&#8377;)</th>
                <th class="text-end">Total Amount&nbsp;(&#8377;)</th>
            </tr>
        </thead>
        <tbody>
            @if($result->count() > 0)
                @php
                    $inv_amt = $sd_amt = $tot_amt = 0; 
                @endphp
                @foreach ($result as $emp)
                    @php
                        $inv_amt += $emp->invoice_amount;
                        $sd_amt += $emp->sd_amount;
                        $tot_amt += $emp->total_amount;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $emp->emp_id }}</td>
                        <td>{{ $emp->emp_name }}</td>
                        <td>{{ $emp->ga_name }}</td>
                        <td class="text-end">{{ $emp->invoice_amount }}</td>
                        <td class="text-end">{{ $emp->sd_amount }}</td>
                        <td class="text-end">{{ $emp->total_amount }}</td>
                    </tr>
                @endforeach
                <tr class="bg-info-subtle fw-semibold">
                    <td colspan="4" class="text-end">Totals</td>
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