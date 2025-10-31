{{-- Prospects Status History --}}
<div id="add-status-history"></div>
<div class="bd-callout bd-callout-primary bg-transparent card mt-0 border-primary mb-3">
    <div class="clearfix mb-2">
        <h4 class="float-start">Status History</h4>
        <div class="float-end">
            <a class="btn btn-sm btn-success" id="add-status-link" href="{{ url('spot/prospectStatus/editStatus/'.$prospect->id.'?type=1') }}"><i class="bi bi-check2-circle"></i>&nbsp;Add / Update Status</a>
        </div>
    </div>
    <a class="visually-hidden" href="{{ url('spot/prospects/'.$prospect->id.'?reload=true&type=1') }}" data-custom-attr="value" id="reload-status">Hidden Link</a>
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
                @if ($prospect->statusHistory->count() > 0)
                    @foreach ($prospect->statusHistory as $history)
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>
                            <td class="align-middle">{{ $history->stage->parent->name ?? $history->stage->name }}</td>
                            <td class="align-middle">{{ $history->stage->name }}</td>
                            <td>{{ $history->notes }}</td>
                            <td>{{ $history->createdBy->first_name }}</td>
                            <td>{{ $history->created_at->format('d-m-Y') }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="6">No status history available.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@include('scripts.ajax-link-id', ['mod' => 'add-status', 'div' => 'action-type'])
<script type="text/javascript">
    // Industrial Area Based on GA
    function getSubStagesByStage(stage)
    {
        let options = '<option value="">select sub stage</option>';
        $("#sub_stage_id").empty();
        $.get("{{ url('spot/prospectStatus/getSubStagesByStage') }}", {stage_id : stage}, function(data) {
            $.each(data.sub_stages, function(index, stage){
                options += `<option value="${stage.id}">${stage.name}</option>`;
            });
            $('#sub_stage_id').html(options);
        });
    }
    // Get Status By Sub Stage Id
    function getDetailsBySubStage(sub_stage, prospect_id) 
    {
        $.get("{{ url('spot/prospectStatus/getDetailsBySubStage') }}", {sub_stage_id : sub_stage, prospect_id : prospect_id}, function(data) {
            $('#subStages_body').html(data);
        });
    }
    // Reload Status History
    function reloadStatusHistory()
    {
        $.get($('#reload-status').attr('href'), function(data) {
            $('#status-history').html(data);
        });
    }
</script>
