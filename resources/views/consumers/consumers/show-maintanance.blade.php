<div class="mt-3">
    <h4 class="fw-semibold text-decoration-underline">Meter Details</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-primary">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Meter Number</th>
                    <th>Meter Serial Number</th>
                    <th>Install Date</th>
                    <th>Install By</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @if($consumer->meter->count() > 0)
                    @foreach ($consumer->meter as $meter)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $meter->meter_no }}</td>
                            <td>{{ $meter->meter_serial_no }}</td>
                            <td>{{ $meter->install_date->format('d-m-Y') }}</td>
                            <td>{{ $meter->installBy->name }}</td>
                            <td>{{ $meter->meterStatus->name }}</td>
                        </tr>
                    @endforeach
                @else
                        <tr>
                            <td colspan="6">No records found</td>
                        </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>