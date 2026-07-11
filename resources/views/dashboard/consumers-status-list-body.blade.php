<div>
    @if (count($reports) > 0)
        <div class="row align-items-center mb-2">
            <div class="col-auto">
                <span class="fs-5">({{ $reports->total() }})&nbsp;Records</span>
            </div>
            <div class="col text-end">
                @php
                    $params = request()->query();
                @endphp
                <a href="{!! url('reports/consumer/onboardingStatusReport/consumerOnboardExport'). '?' . http_build_query($params) !!}" 
                class="btn btn-outline-primary">
                    <i class="bi bi-file-earmark-excel"></i>&nbsp;<small>Export</small>
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-info">
                    <tr>
                        <th width="1%">S.No</th>
                        <th nowrap>Geo Area</th>
                        <th>CRN</th>
                        <th>Connection Type</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Status Date</th>
                        <th>Updated By</th>
                        <th>Created Date</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = (($reports->currentPage() - 1) * $reports->perPage())+1;
                    @endphp
                    @foreach ($reports as $report)
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>
                            <td nowrap>{{ $report->consumer->ga->name }}</td>
                            <td><a href="{{ url('consumers/'.$report->consumer->id) }}" target="_blank">{{ $report->consumer->crn ?? $report->consumer->t_crn }}</a></td>
                            <td>{{ $report->consumer->connection_type_id == "1" ? "Postpaid" : "Prepaid" }}</td>
                            <td>{{ $report->consumer->fname }}&nbsp;{{ $report->consumer->lname }}</td>
                            <td>{{ $report->status->name }}</td>
                            <td>{{ dateFormat($report->created_at) }}</td>
                            <td>{{ $report->createdBy?->name }}</td>
                            <td>{{ $report->consumer->created_at?->format('d-m-Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- load utils file for pagination --}}
        <div class="col-sm-12">
            {{ $reports->links('utils.paginator', ['modDiv' => 'reports-list']) }}
        </div>
    @else
        <div class="p-3 m-2 alert alert-info">
            Consumers not found
        </div>
    @endif
</div>