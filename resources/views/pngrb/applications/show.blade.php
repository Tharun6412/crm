<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">
                Application Information - {{ $application->applicationNumber }}
            </h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex">
                    <strong class="w-50">Application Number</strong>
                    <span>{{ $application->applicationNumber }}</span>
                </li>
                <li class="list-group-item d-flex">
                    <strong class="w-50">EKYC Status</strong>
                    <span>{{ $application->ekycStatus }}</span>
                </li>
                <li class="list-group-item d-flex">
                    <strong class="w-50">Serviceability Status</strong>
                    <span>{{ $application->serviceabilityStatus }}</span>
                </li>
                <li class="list-group-item d-flex">
                    <strong class="w-50">Name</strong>
                    <span>{{ $application->name }}</span>
                </li>
                <li class="list-group-item d-flex">
                    <strong class="w-50">Date Of Birth</strong>
                    <span>{{ dateFormat($application->dob) }}</span>
                </li>
                <li class="list-group-item d-flex">
                    <strong class="w-50">Mobile</strong>
                    <span>{{ $application->mobileNumber }}</span>
                </li>
                <li class="list-group-item d-flex">
                    <strong class="w-50">Whatsapp</strong>
                    <span>{{ $application->whatsapp }}</span>
                </li>
                <li class="list-group-item d-flex">
                    <strong class="w-50">Email</strong>
                    <span>{{ $application->email }}</span>
                </li>
                <li class="list-group-item d-flex">
                    <strong class="w-50">Address</strong>
                    <span>{{ $application->houseNo }}</span>
                </li>
                <li class="list-group-item d-flex">
                    <strong class="w-50">District</strong>
                    <span>{{ $application->district }}</span>
                </li>
                <li class="list-group-item d-flex">
                    <strong class="w-50">State</strong>
                    <span>{{ $application->state }}</span>
                </li>
                <li class="list-group-item d-flex">
                    <strong class="w-50">Pincode</strong>
                    <span>{{ $application->pincode }}</span>
                </li>
                <li class="list-group-item d-flex">
                    <strong class="w-50">Latitude</strong>
                    <span>{{ $application->latitude }}</span>
                </li>
                <li class="list-group-item d-flex">
                    <strong class="w-50">Longitude</strong>
                    <span>{{ $application->longitude }}</span>
                </li>
                <li class="list-group-item d-flex">
                    <strong class="w-50">Status</strong>
                    <span>{{ $application->status }}</span>
                </li>
                <li class="list-group-item d-flex">
                    <strong class="w-50">Created Date</strong>
                    <span>{{ $application->created_at?->format('d-m-Y') }}</span>
                </li>
            </ul>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x"></i> Close</button>
        </div>
    </div>
</div>