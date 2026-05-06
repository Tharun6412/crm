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
                <a href="{!! url('reports/consumer/conversions/consumerPrepaidExport'). '?' . http_build_query($params) !!}" 
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
                        <th>Conversion Date</th>
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
                            <td nowrap>{{ $report->consumers->ga->name }}</td>
                            <td>{{ $report->consumers->crn ?? $report->consumers->t_crn }}</td>
                            <td>{{ $report->consumers->connection_type_id == "1" ? "Postpaid" : "Prepaid" }}</td>
                            <td>{{ $report->consumers->fname }}&nbsp;{{ $report->consumers->lname }}</td>
                            <td>{{ $report->consumers->status->name }}</td>
                            <td>{{ dateFormat($report->conversion_date) }}</td>
                            <td>{{ $report->consumers->createdBy?->name }}</td>
                            <td>{{ $report->consumers->created_at?->format('d-m-Y') }}</td>
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