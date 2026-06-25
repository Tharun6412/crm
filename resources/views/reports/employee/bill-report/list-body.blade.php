{{-- Employee billing report body view --}}

<div>
    <span class="mb-2"><h5>({{ $employee_bills->count() }}) &nbsp;Records Found.</h5></span>
    <table class="table table-bordered table-striped" id="employee-report">
        <thead class="table-success">
            <tr>
                <th width="1%">S.No</th>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th class="text-end">Invoice Count</th>
            </tr>
        </thead>
        <tbody>
            @if ($employee_bills->count() > 0)
                @foreach ($employee_bills as $employee)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $employee->emp_id }}</td>
                        <td>{{ $employee->name }}</td>
                        <td class="text-end">{{ numberFormat($employee->invoice_count) }}</td>
                    </tr>
                @endforeach
                <tfoot>
                    <tr class="fw-semibold">
                        <td colspan="3" class="text-end">Total</td>
                        <td class="text-end">{{ numberFormat($employee_bills->sum('invoice_count')) }}</td>
                    </tr>
                </tfoot>
            @else
                <tr>
                    <td colspan="4">No reocrds found!</td>
                </tr>
            @endif
        </tbody>
    </table>