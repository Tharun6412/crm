<?php
/**
 * Prospect pipeline details
 */
?>
<div class="bd-callout bd-callout-primary bg-transparent card mt-0 border-primary mb-3">
    <h4>Pipeline Details</h4>
    @if (isset($prospect_data['lead_pipelines']) and !empty($prospect_data['lead_pipelines']))
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
                    @foreach ($prospect_data['lead_pipelines'] as $key => $pipeline)
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>
                            <td></td>
                            <td class="text-end"></td>
                            <td class="text-center"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
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