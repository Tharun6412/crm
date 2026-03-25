<div>
    @if (count($reports) > 0)
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
                        <th>Added Date</th>
                        <th>Added By</th>
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
                            <td>{{ $report->consumer->crn }}</td>
                            <td>{{ $report->consumer->connection_type_id == "1" ? "Postpaid" : "Prepaid" }}</td>
                            <td>{{ $report->consumer->fname }}&nbsp;{{ $report->consumer->lname }}</td>
                            <td>{{ $report->status->name }}</td>
                            <td>{{ dateFormat($report->created_at) }}</td>
                            <td>{{ $report->createdBy?->name }}</td>
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