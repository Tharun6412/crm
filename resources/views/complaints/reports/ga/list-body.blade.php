<div class="bg-white p-2 border border-top-0">
    <form class="report-filter-form" action="{{ url('calls/reports/gaReport') }}" data-target="#nav-ga">
        <div class="d-flex justify-content-between mt-2">
            <div class="d-flex gap-2">
                <div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">From Date</span>
                        <input type="text" class="form-control" aria-label="From Date" name="date_from" id="date_from" value="{{ $date_from->format('d-m-Y') }}">
                        <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                    </div>
                </div>
                <div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">To Date</span>
                        <input type="text" class="form-control" aria-label="To Date" name="date_to" id="date_to" value="{{ $date_to->format('d-m-Y') }}">
                        <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
                </div>
                <!-- Reset -->
                <div>
                    <a href="{{ url('calls/reports/gaReport') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </div>
            <div class="text-end">
                <button type="button" id="exportGBtn" class="btn btn-outline-info text-end"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</button>
            </div>
        </div>
    </form>
    <table class="table table-bordered table-striped bg-white table-hover" id="ga-report">
        <thead class="table-success">
            <tr>
                <th>GA</th>
                @foreach($statuses as $status)
                    <th nowrap class="text-end">{{ $status->name }}</th>
                @endforeach
                <th class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $statusTotals = [];
                $grandTotal = 0;
                foreach($statuses as $status){
                    $statusTotals[$status->id] = 0;
                }
            @endphp
            @foreach($gaComplaints as $ga)
                <tr>
                    <td nowrap>{{ $ga->name }}</td>
                    @foreach($statuses as $status)
                        @php
                            $value = $ga->{'status_'.$status->id} ?? 0;
                            $statusTotals[$status->id] += $value;
                        @endphp
                        <td class="text-end">
                            <a href="{{ url('calls') }}?{{ http_build_query([
                            'geo_area' => [$ga->id],
                            'cmp_status' => [$status->id],
                            'date_from' => $date_from->format('d-m-Y'),
                            'date_to' => $date_to->format('d-m-Y'), 
                            ])}}" class="aging-link" target="_blank" >{{ $value }}</a>
                        </td>
                    @endforeach
                    <td class="text-end">{{ $ga->total }}</td>
                    @php
                        $grandTotal += $ga->total;
                    @endphp
                </tr>
            @endforeach
            <tr class="table-info fw-semibold">
                <td class="text-end">Total</td>
                @foreach($statuses as $status)
                    <td class="text-end">{{ $statusTotals[$status->id] ?? 0 }}</td>
                @endforeach
                <td class="text-end">{{ $grandTotal }}</td>
            </tr>
        </tbody>
    </table>
</div>
@include('scripts.datepicker', ['list' => ['date_from', 'date_to']])
@include('scripts.export-table', [
    'table' => 'ga-report',
    'button' => 'exportGBtn',
    'tabBased' => false,
    'filename' => 'ga_report',
    'sheet'    => 'Report',
])