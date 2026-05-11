<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">
                Geyser Details - {{ $geyser->code }}
            </h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <x-consumer.basic-details :consumer="$geyser->consumer" class="bg-info-subtle"/> 
        <x-geyser.geyser-details :geyser="$geyser" class="bg-info-subtle"/>  
        <x-geyser.statushistory :geyser="$geyser" class="bg-info-subtle"/>   
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x"></i> Close</button>
        </div>

    </div>
</div>