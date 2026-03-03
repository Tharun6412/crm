<div class="mt-3">
    <div class="bg-primary-subtle p-2 fs-5 fw-semibold">
        <i class="bi bi-card-heading"></i>&nbsp;Meter Details
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-primary">
            <thead>
                <tr class="bg-body-secondary">
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
                            <td><x-consumer.status :status="$consumer->status" /></td>
                            {{-- {{ $meter->meterStatus->name }} --}}
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