<?php
/**
 * Prospect pipeline details
 */
?>
<div id="pipeline_success">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <strong><i class="bi bi-check2-circle"></i>&nbsp;Success</strong>&nbsp;{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
<a class="visually-hidden" href="{{ url('spot/prospects/'.$prospect->id.'?reload=true&type=3') }}" data-custom-attr="value" id="reload-pipeline">Hidden Link</a>
<div class="bd-callout bd-callout-primary bg-transparent card mt-0 border-primary mb-3">
    <h4>Pipeline Details</h4>
    @if ($prospect->pipeLineHistory->count() > 0)
        <div class="table-responsive spot-table">
            <table class="table table-bordered table-hover table-sm table-striped mb-0">
                <thead>
                    <tr class="spot-table-bg">
                        <th nowrap class="text-center">S.No</th>
                        <th nowrap >Type of pipeline</th>
                        <th nowrap >Length <small>(Km)</small></th>
                        <th nowrap >Status</th>
                        <th nowrap >Added By</th>
                        <th nowrap >Added Date</th>
                        <th nowrap >Updated By</th>
                        <th nowrap >Updated Date</th>
                        <th nowrap >Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($prospect->pipeLineHistory as $pipeline)
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>
                            <td>{{ $pipeline->pipe_type == "1" ? "Steel Pipeline" : "MDPE Pipeline"}}</td>
                            <td class="text-end"> {{ $pipeline->length }}</td>
                            <td class="text-center">
                                @if ($pipeline->status == 1)
                                    <span class='badge text-success border border-success'><i class='bi bi-check'></i>&nbsp;Completed</span>
                                @else
                                    <span class='badge text-warning border border-warning'><i class='bi bi-x'></i>&nbsp;Pending</span>
                                @endif
                            </td>
                            <td>{{ $pipeline->createdBy->first_name }}&nbsp;{{ $pipeline->createdBy->last_name }}</td>
                            <td>{{ $pipeline->created_at?->format('d-m-Y H:i:s') }}</td>
                            <td>{{ $pipeline->updatedBy->first_name }}&nbsp;{{ $pipeline->updatedBy->last_name }} </td>
                            <td>{{ $pipeline->updated_at?->format('d-m-Y H:i:s') }}</td>
                            <td>
                                @if ($pipeline->status == "0")
                                    <a href="javascript:void(0);" onclick="compeltePipeline('{{ $pipeline->id }}', '{{ $pipeline->prospect_id }}');" class="btn btn-success btn-sm" title="Completed Pipeline"><i class="bi bi-check2-square"></i></a>                                    
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
<script type="text/javascript">
    function compeltePipeline(id, prospect_id)
    {
        if(confirm("Are you sure you want to mark it as complete")) {
            $.post("{{ url('spot/prospects/updatePipeLine') }}", {_token : '{{ csrf_token() }}', id:id, prospect_id:prospect_id}, function(data) {
                $.get($('#reload-pipeline').attr('href'), function(data) {
                    $('#prospect-pipeline').html(data);
                });
            });
        }
    }
</script>