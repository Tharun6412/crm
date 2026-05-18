<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">
                Lms Details - {{ $leads->code }}
            </h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <x-lms.lead-details :leads="$leads" class="bg-info-subtle"/>  
            <x-lms.statushistory :leads="$leads" class="bg-info-secondary" />
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x"></i> Close</button>
        </div>

    </div>
</div>