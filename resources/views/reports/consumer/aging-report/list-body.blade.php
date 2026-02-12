<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover page-sort">
        <thead class="table-success">
            <tr>
                <th>GA ID</th>
                <th>GA Name</th>
                <th>1–15 Days</th>
                <th>16–30 Days</th>
                <th>31–60 Days</th>
                <th>61–90 Days</th>
                <th>> 90 Days</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @forelse($gasAging as $ga)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $ga->ga_name }}</td>
                    @foreach([
                        'range_1_15' => '1-15',
                        'range_16_30' => '16-30',
                        'range_31_60' => '31-60',
                        'range_61_90' => '61-90',
                        'range_gt90'  => '90+'
                    ] as $field => $range)

                        <td>
                            <a href="{{ url('reports/ageingReport/invoicesList') }}?{{ http_build_query([
                                'ga_id' => $ga->ga_id,
                                'range' => $range,
                                ]) }}" class="aging-link link-modal">{{ $ga->$field }}
                            </a>
                        </td>

                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No GA Records Found</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">Total</th>
                <th>{{ $gasAging->sum('range_1_15') }}</th>
                <th>{{ $gasAging->sum('range_16_30') }}</th>
                <th>{{ $gasAging->sum('range_31_60') }}</th>
                <th>{{ $gasAging->sum('range_61_90') }}</th>
                <th>{{ $gasAging->sum('range_gt90') }}</th>
            </tr>
        </tfoot>
    </table>
</div>
{{-- Scripts --}}
@push('scripts')
    @include('scripts.link-modal')
@endpush