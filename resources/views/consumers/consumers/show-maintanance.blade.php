<div class="mt-1">
    <div class="bg-primary-subtle p-2 fs-5 fw-semibold">
        <i class="bi bi-box"></i>&nbsp;Meter Details
    </div>
    <div class="table-responsive p-2">
        <table class="table table-bordered table-primary">
            <thead>
                <tr class="bg-primary-subtle">
                    <th width="1%">S.No</th>
                    <th>Meter Number</th>
                    <th>Meter Serial Number</th>
                    <th>Installed Date</th>
                    <th>Installed By</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @if($consumer->meter->count() > 0)
                    @foreach ($consumer->meter as $meter)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $meter->meter_no }}</td>
                            <td>{{ $meter->meter_serial_no }}</td>
                            <td>{{ $meter->install_date?->format('d-m-Y') }}</td>
                            <td>{{ $meter->installBy->name }}</td>
                            <td>{{ $meter->meterStatus->name }}</td>
                            {{--  --}}
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