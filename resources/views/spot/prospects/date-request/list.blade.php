<?php
/**
 * Date change requests
 */
?>
<div class="bd-callout bd-callout-primary bg-transparent card mt-0 border-primary mb-3">
    <div class="clearfix mb-2">
        <h4 class="float-start">Date Change Requests</h4>
        <div class="float-end">
            <button class="btn btn-success btn-sm" type="button" onclick="raiseRequestDate(<? print $prospect_data['id']; ?>)"><i class="bi bi-calendar-plus"></i>&nbsp;Add Request</button>
        </div>
    </div>
    @if (isset($prospect_data['date_change_history']) and !empty($prospect_data['date_change_history']))
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
                    <?
                    $i = 1;
                    foreach ($prospect_data['date_change_history'] as $key => $req) { ?>
                        <tr>
                            <td class="text-center"><?= $i++; ?></td>
                            <td class="align-middle" nowrap><?= date('d-m-Y', strtotime($req['cur_date'])); ?></td>
                            <td class="align-middle" nowrap><?= date('d-m-Y', strtotime($req['new_date'])); ?></td>
                            <td class="text-center align-middle">
                                <?
                                    if ($req['status'] == 1) echo "<span class='badge text-success border border-success'><i class='bi bi-check'></i>&nbsp;Approved</span>";
                                    else if ($req['status'] == 2) echo "<span class='badge text-danger border border-danger'><i class='bi bi-x'></i>&nbsp;Rejected</span>";
                                    else echo "<span class='badge text-warning border border-warning'><i class='bi bi-pause-circle'></i>&nbsp;Pending</span>";
                                ?>
                            </td>
                            <td><?= $req['note']; ?></td>
                            <td><?= $req['created_by_name']; ?></td>
                            <td class="align-middle"><?= date('d-m-Y H:i', strtotime($req['created_at'])); ?></td>
                            <td><?= $req['approved_by_name'] ?? '--'; ?></td>
                            <td class="align-middle"><?= isset($req['approved_at']) ? date('d-m-Y H:i', strtotime($req['approved_at'])) : '--'; ?></td>
                            <td nowrap>
                                <? 
                                // Check if the request status is 'requested' and if the logged role is 1 (Admin), 3 (GA Head), 4 (Cluster Head), or 5 (HO Sales)
                                if ($req['status'] == 0  AND ($this->spotaccess->isAdmin() OR $this->spotaccess->isHoSales() OR $this->spotaccess->isClusterHead() OR $this->spotaccess->isGaHead())) { 
                                    ?>
                                    <a href="javascript:void(0);" onclick="approveDateRequest(<?= $req['id']; ?>, <? print $req['lead_id']; ?>);" class="btn btn-success btn-sm" title="Approve"><i class="bi bi-check-lg"></i></a>
                                    <a href="javascript:void(0);" onclick="rejectDateRequest(<?= $req['id']; ?>, <? print $req['lead_id']; ?>);" class="btn btn-danger btn-sm" title="Reject"><i class="bi bi-x-lg"></i></a>
                                    <?
                                }
                                else {
                                    echo '--';
                                }
                                ?>
                            </td>
                        </tr>
                        <?
                    }
                    ?>
                </tbody>
            </table>
        </div>
    @else
        <div class='alert alert-warning mb-0'>No records found!</div>
    @endif
</div>
<div id="date_chng_rqst_form">
</div>

<script type="text/javascript">
    $(document).ready(function() {
        setTimeout(function(){
            $('#alert_data').fadeOut();
        }, 30000);
    });
</script>
