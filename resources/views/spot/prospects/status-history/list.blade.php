{{-- Prospects Status History --}}
<div class="bd-callout bd-callout-primary bg-transparent card mt-0 border-primary mb-3">
    <div class="clearfix mb-2">
        <h4 class="float-start">Status History</h4>
        <div class="float-end">
            <a class="btn btn-sm btn-success ajax-link" href="{{ url('spot/prospects/editStatus/'.$prospect->id.'?type=1') }}"><i class="bi bi-check2-circle"></i>&nbsp;Add / Update Status</a>
        </div>
    </div>
    <div class="table-responsive spot-table">
        <table class="table table-bordered table-hover table-sm table-striped mb-0">
            <thead>
                <tr class="spot-table-bg">
                    <th width="1%" class="text-center">S.No.</th>
                    <th class="text-center">Stage</th>
                    <th class="text-center">Sub Stage</th>
                    <th>Notes</th>
                    <th>Updated By</th>
                    <th>Updated Date</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @if (isset($prospect_data['status_history']) and !empty($prospect_data['status_history']))
                    @foreach ($prospect_data['status_history'] as $key => $history)
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>
                            <td class="align-middle"></td>
                            <td class="align-middle"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="6">No status history available.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>