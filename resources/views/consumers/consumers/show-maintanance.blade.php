<div class="mt-1">
    <div class="bg-primary-subtle p-2 fs-5 fw-semibold">
        <i class="bi bi-speedometer2"></i>&nbsp;Meter Details
    </div>
    <div class="p-2">
        @if($consumer->meter->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-primary">
                    <thead>
                        <tr class="bg-primary-subtle">
                            <th width="1%">S.No</th>
                            <th>Meter Number</th>
                            <th>Serial Number</th>
                            <th class="text-end">Initial Reading</th>
                            <th>Installed Date</th>
                            <th>Installed By</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($consumer->meter as $meter)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $meter->meter_no }}</td>
                                <td>{{ $meter->meter_serial_no }}</td>
                                <td class="text-end">{{ numberFormat($meter->initial_reading ?? 0, 3) }}</td>
                                <td>{{ $meter->install_date?->format('d-m-Y') }}</td>
                                <td>{{ $meter->installBy?->name }}</td>
                                <td>
                                    @if ($meter->meterStatus->name == 'Active')
                                        <span class="badge rounded-pill text-bg-primary">Active</span>
                                    @else
                                         <span class="badge rounded-pill text-bg-success">Replaced</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ url('meterDetails') }}" class="btn btn-outline-primary btn-sm">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info mb-0">
                No records found!
            </div>
        @endif
    </div>
</div>