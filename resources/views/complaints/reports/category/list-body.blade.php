<div class="mt-3">
    <form class="report-filter-form" action="{{ url('calls/reports/categoryReport') }}" data-target="#nav-ga">
        <div class="d-flex justify-content-between">
            <div class="row gx-1 mb-1">
                <div class="col-auto">
                    <div class="input-group mb-3">
                        <span class="input-group-text">From Date</span>
                        <input type="text" class="form-control" aria-label="From Date" name="date_from" id="date_from" value="{{ $date_from->format('d-m-Y') }}">
                        <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="input-group mb-3">
                        <span class="input-group-text">To Date</span>
                        <input type="text" class="form-control" aria-label="To Date" name="date_to" id="date_to" value="{{ $date_to->format('d-m-Y') }}">
                        <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
                </div>
                <!-- Reset -->
                <div class="col-auto">
                    <a href="{{ url('calls/reports/categoryReport') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </div>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Category</th>
                @foreach($statuses as $status)
                    <th nowrap>{{ $status->name }}</th>
                @endforeach
                <th>Total</th>
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
            @foreach($categoryComplaints as $ga)
                <tr>
                    <td nowrap>{{ $ga->name }}</td>
                    @foreach($statuses as $status)
                        @php
                            $value = $ga->{'status_'.$status->id} ?? 0;
                            $statusTotals[$status->id] += $value;
                        @endphp
                        <td>
                            <a href="{{ url('calls') }}?{{ http_build_query([
                            // 'geo_area' => [$ga->id],
                            'category' => [$ga->id],
                            'cmp_status' => [$status->id],
                            'date_from' => $date_from->format('d-m-Y'),
                            'date_to' => $date_to->format('d-m-Y'), 
                            ])}}" class="aging-link" target="_blank" >{{ $value }}</a>
                        </td>
                    @endforeach
                    <td>{{ $ga->total }}</td>
                    @php
                        $grandTotal += $ga->total;
                    @endphp
                </tr>
            @endforeach
            <tr>
                <td>Total</td>
                @foreach($statuses as $status)
                    <td>{{ $statusTotals[$status->id] ?? 0 }}</td>
                @endforeach
                <td>{{ $grandTotal }}</td>
            </tr>
        </tbody>
    </table>
</div>
@include('scripts.datepicker', ['list' => ['date_from', 'date_to']])
