<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">View New Meter Details</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Consumer basic details --}}
            <x-consumer.basic-details :consumer="$consumer_meter->consumer" class="bg-info-subtle" type="3" />
                {{-- Meter Change Details --}}
            <div class="row g-2 pb-2 mb-2">
                <div class="fw-semibold text-decoration-underline">New Meter Details</div>
                <div class="col-sm-2 text-end fw-semibold">Meter Number : </div>
                <div class="col-sm-4">{{ $consumer_meter->newMeter->meter_no }}</div>
                <div class="col-sm-2 text-end fw-semibold">Meter Serial Number : </div>
                <div class="col-sm-4">{{ $consumer_meter->newMeter->meter_serial_no }}</div>
                <div class="col-sm-2 text-end fw-semibold">Request Date : </div>
                <div class="col-sm-4">{{ $consumer_meter->request_date->format('d-m-Y') }}</div>
                <div class="col-sm-2 text-end fw-semibold">Release Date : </div>
                <div class="col-sm-4">{{ $consumer_meter->replace_date->format('d-m-Y') }}</div>
                <div class="col-sm-2 text-end fw-semibold">Initial Reading(SCM) : </div>
                <div class="col-sm-4">{{ $consumer_meter->newMeter->initial_reading }}</div>
                <div class="col-sm-2 text-end fw-semibold">Install Date : </div>
                <div class="col-sm-4">{{ $consumer_meter->newMeter->install_date?->format('d-m-Y') }}</div>
                <div class="col-sm-2 text-end fw-semibold">Assigned Technician : </div>
                <div class="col-sm-4">{{ $consumer_meter->technician->name }}</div>
                <div class="col-sm-2 text-end fw-semibold">Status : </div>
                <div class="col-sm-4">{{ $consumer_meter->status_id == "1" ? "Pending" : "Closed" }}</div>
            </div>
            {{-- old Meter Details --}}
            <div class="row g-2 pb-2 mb-2">
                <div class="fw-semibold text-decoration-underline">Old Meter Details</div>
                <div class="col-sm-2 text-end fw-semibold">Meter Number : </div>
                <div class="col-sm-4">{{ $consumer_meter->meter->meter_no }}</div>
                <div class="col-sm-2 text-end fw-semibold">Meter Serial Number : </div>
                <div class="col-sm-4">{{ $consumer_meter->meter->meter_serial_no }}</div>
                <div class="col-sm-2 text-end fw-semibold">Previous Reading : </div>
                <div class="col-sm-4">{{ $consumer_meter->prev_reading }}</div>
                <div class="col-sm-2 text-end fw-semibold">End Reading : </div>
                <div class="col-sm-4">{{ $consumer_meter->end_reading }}</div>
                <div class="col-sm-2 text-end fw-semibold">Consumption(SCM) : </div>
                <div class="col-sm-4">{{ $consumer_meter->consumption }}</div>
                <div class="col-sm-2 text-end fw-semibold">Meter Status : </div>
                <div class="col-sm-4">{{ $consumer_meter->meter->meterStatus->name }}</div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
