<?php
/**
 * Date change requests
 */
?>
<div id="date-request-approved">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <strong><i class="bi bi-check2-circle"></i>&nbsp;Success</strong>&nbsp;{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
<div class="bd-callout bd-callout-primary bg-transparent card mt-0 border-primary mb-3">
    <div class="clearfix mb-2">
        <h4 class="float-start">Date Change Requests</h4>
        <div class="float-end">          
            <a class="btn btn-sm btn-success ajax-link" href="{{ url('spot/prospectDateChangeRequest/create/'.$prospect->id.'?type=7') }}"><i class="bi bi-calender-plus"></i>&nbsp;Add Request</a>
        </div>
    </div>
    <a class="visually-hidden" href="{{ url('spot/prospects/'.$prospect->id.'?reload=true&type=7') }}" data-custom-attr="value" id="reload-date-request">Hidden Link</a>
    @if ($prospect_date_change_history->count() > 0)
        <div class="table-responsive spot-table">
            <table class="table table-bordered table-hover table-sm table-striped mb-0">
                <thead>
                    <tr class="spot-table-bg">
                        <th nowrap class="text-center">S.No</th>
                        <th nowrap >Current Date</th>
                        <th nowrap >New Date</th>
                        <th nowrap >Status</th>
                        <th nowrap >Notes</th>
                        <th nowrap >Requested By</th>
                        <th nowrap >Requested Date</th>
                        <th nowrap >Approved By</th>
                        <th nowrap >Approved Date</th>
                        <th nowrap >Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;                        
                    @endphp
                    @foreach ($prospect_date_change_history as $history)
                        <tr>
                            <td class="text-center"><?= $i++; ?></td>
                            <td class="align-middle" nowrap>{{ $history->current_date?->format('d-m-Y') }}</td>
                            <td class="align-middle" nowrap>{{ $history->new_date?->format('d-m-Y') }}</td>
                            <td class="text-center align-middle">
                                @switch($history->status)
                                @case(1)
                                <span class='badge text-success border border-success'><i class='bi bi-check'></i>&nbsp;Approved</span>
                                @break
                                @case(2)
                                <span class='badge text-danger border border-danger'><i class='bi bi-check'></i>&nbsp;Rejected</span>
                                @break
                                @default
                                <span class='badge text-warning border border-warning'><i class='bi bi-pause-circle'></i>&nbsp;Pending</span>
                                @endswitch
                            </td>
                            <td>{{ $history->note }}</td>
                            <td>{{ $history->createdBy->first_name }}</td>
                            <td class="align-middle">{{ $history->created_at?->format('d-m-Y') }}</td>
                            <td>{{ $history->approvedBy->first_name }}</td>
                            <td class="align-middle">{{ $history->approved_at?->format('d-m-Y') }}</td>
                            <td nowrap>
                                @if($history->status == "0")
                                <a href="javascript:void(0);" onclick="approveDateRequest('{{ $history->id }}', '{{ $history->prospect_id }}')" class="btn btn-success btn-sm" title="Approve"><i class="bi bi-check-lg"></i></a>
                                <a href="javascript:void(0);" onclick="rejectDateRequest('{{ $history->id }}', '{{ $history->prospect_id }}')" class="btn btn-danger btn-sm" title="Reject"><i class="bi bi-x-lg"></i></a>
                                @else
                                {{ " " }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class='alert alert-warning mb-0'>No records found!</div>
    @endif
</div>
@include('scripts.ajax-link', ['div' => 'action-type'])
<script type="text/javascript">
// To Approve the request
    function approveDateRequest(id, prospect_id)
    {
        if(confirm("Are you sure you want to approve the date request")) {
            $.post("{{ url('spot/prospectDateChangeRequest/approve') }}", {_token: '{{ csrf_token() }}', id:id, prospect_id : prospect_id}, function(data) {
                $.get($('#reload-date-request').attr('href'), function(data) {
                    $('#date-request').html(data);
                });
            });
        }
    }
    // TO Reject the request
    function rejectDateRequest(id, prospect_id)
    {
        if(confirm("Are you sure you want to reject the date request")) {
            $.post("{{ url('spot/prospectDateChangeRequest/reject') }}", {_token: '{{ csrf_token() }}', id:id, prospect_id : prospect_id}, function(data) {
                $.get($('#reload-date-request').attr('href'), function(data) {
                    $('#date-request').html(data);
                });
            });
        }
    }
</script>
