{{-- Show consumer details, tab content --}}

<div class="border rounded-top">
    <div class="bg-light p-2 fs-5 fw-semibold">
        <i class="bi bi-person"></i>&nbsp;Consumer Details
    </div>
    <div class="p-2">
        <div class="row">
            <div class="col-md-6">
                <h4 class="fw-semibold text-decoration-underline">Details</h4>
                <dl class="row">
                    <dt class="col-sm-3">Segment</dt>
                    <dd class="col-sm-9">{{ $consumer->segment->name }}</dd>
                    <dt class="col-sm-3">CRN</dt>
                    <dd class="col-sm-9">{{ $consumer->crn }}</dd>
                    <dt class="col-sm-3">Name</dt>
                    <dd class="col-sm-9">{{ $consumer->name }}</dd>
                    <dt class="col-sm-3">Email</dt>
                    <dd class="col-sm-9">{{ $consumer->email }}</dd>
                    <dt class="col-sm-3">Status</dt>
                    <dd class="col-sm-9">{{ $consumer->status->name }}</dd>
                </dl>

                <h4 class="mt-3 fw-semibold text-decoration-underline">Scheme Details</h4>
                <dl class="row">
                    <dt class="col-sm-3">Scheme</dt>
                    <dd class="col-sm-9">{{ $consumer->scheme?->scheme?->name }}</dd>
                    <dt class="col-sm-3">Payment</dt>
                    <dd class="col-sm-9">{{ $consumer->scheme?->scheme?->total_deposit }}</dd>
                </dl>

                <h4 class="mt-3 fw-semibold text-decoration-underline">More Details</h4>
                <dl class="row">
                    <dt class="col-sm-3">Title</dt>
                    <dd class="col-sm-9">Details</dd>
                    <dt class="col-sm-3">Title</dt>
                    <dd class="col-sm-9">Details</dd>
                </dl>
            </div>
            <div class="col-md-6">
                <h4 class="fw-semibold text-decoration-underline">Location</h4>
                <dl class="row">
                    <dt class="col-sm-3">Geo Area</dt>
                    <dd class="col-sm-9">{{ $consumer->ga->name }}</dd>
                    <dt class="col-sm-3">Charge Area</dt>
                    <dd class="col-sm-9">{{ $consumer->ca->name ?? '' }}</dd>
                    <dt class="col-sm-3">Location</dt>
                    <dd class="col-sm-9">{{ $consumer->area->name ?? '' }}</dd>
                </dl>

                <h4 class="mt-3 fw-semibold text-decoration-underline">Address</h4>
                <address>
                    <strong>{{ $consumer->name}}</strong><br>
                    {{ $consumer->cof->name ?? '' }} {{ $consumer->cof_name ?? '' }}<br>
                    {{ $consumer->hno }}, {{ $consumer->street }},<br>
                    {{ $consumer->colony }}, {{ $consumer->city }},<br>
                    {{ $consumer->district->name ?? '' }}, {{ $consumer->state->name ?? '' }} - {{ $consumer->pincode }}.
                </address>

                <h4 class="mt-3 fw-semibold text-decoration-underline">Meter Details</h4>
                <dl class="row">
                    <dt class="col-sm-3">Meter Number</dt>
                    <dd class="col-sm-9">{{ $consumer->meter?->meter_no }}</dd>
                    <dt class="col-sm-3">Serial Number</dt>
                    <dd class="col-sm-9">{{ $consumer->meter?->meter_serial_no }}</dd>
                    <dt class="col-sm-3">Initial Reading</dt>
                    <dd class="col-sm-9">{{ $consumer->meter?->initial_reading }}</dd>
                    <dt class="col-sm-3">Installation Date</dt>
                    <dd class="col-sm-9">{{ $consumer->meter?->install_date?->format('d-m-Y') }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>