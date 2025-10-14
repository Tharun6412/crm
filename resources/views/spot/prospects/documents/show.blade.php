<?php
/**
 * Prospect documents list
 */
?>
<div class="bd-callout bd-callout-primary bg-transparent card mt-0 border-primary mb-3">
    <div class="clearfix mb-2">
        <h4 class="float-start">Documents</h4>
        <div class="float-end">
            <button class="btn btn-sm btn-success" type="button" onclick="manageDocs(<? echo $prospect_data['id'] ?>)"><i class="bi bi-file-earmark-plus"></i>&nbsp;Add Document</button>
        </div>
    </div>
    @php
        $i = 1;
    @endphp
    @if (isset($lead_documents) and !empty($lead_documents))
        <div class="table-responsive spot-table">
            <table class="table table-bordered table-hover table-striped table-sm align-middle mb-0">
                <thead>
                    <tr class="spot-table-bg">
                        <th class="text-center">S.No.</th>
                        <!-- <th nowrap class="text-center">Document</th> -->
                        <th nowrap>Document</th>
                        <th nowrap>Document Type</th>
                        <th>Status</th>
                        <th nowrap>Create date</th>
                        <th nowrap>Created By</th>
                        <th nowrap>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($lead_documents as $key => $document) { ?>
                        <!-- <a href="<? print WEB_ROOT . $document['path']; ?>"><? print $document['file_name']; ?></a> -->
                        <tr>
                            <td class="text-center"><? print $i++;?></td>
                            <!-- <td class="text-center"><img src="<? print WEB_ROOT . $document['path']; ?>" alt="Documents img" height="55"></td> -->
                            <td class="text-center">
                                <a href="<? print WEB_ROOT . $document['path']; ?>" title="<? print $document['file_name']; ?>" target="_blank"><i class="bi bi-file-earmark-pdf fs-5 text-danger"></i></a>
                            </td>
                            <td><? print $document['document_type']; ?>&nbsp;<? echo $document['offer_count']; ?></td>
                            <td>
                                <? echo ($document['status'] == 1 and $document['win'] == 1) ? "Win" : (($document['status'] == 1) ? "Approved" : (($document['status'] == 2) ? 'Rejected' : '--')); ?>
                            </td>
                            <td><? print (isset($document['created_at']) and !empty($document['created_at'])) ? date('d-m-Y',strtotime($document['created_at'])) : "-"; ?></td>
                            <td><? print $document['employee']; ?></td>
                            <td nowrap>
                                <a href="<? print WEB_ROOT . $document['path']; ?>" class="btn btn-sm btn-outline-primary" target="_blank"><i class="bi bi-file-earmark-pdf"></i></a>
                                <?
                                if($this->spotaccess->isAdmin() OR $this->spotaccess->isClusterHead()) {
                                    if($document['status'] != 1) {
                                        ?>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger" onclick="deleteDocs(<? print $document['id']; ?>, <? print $document['lead_id']; ?>, 1)" title="Delete document"><i class="bi bi-trash"></i></a>
                                        <?
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    @else
        <div class='alert alert-warning mb-0'>No records found!</div>
    @endif
</div>
