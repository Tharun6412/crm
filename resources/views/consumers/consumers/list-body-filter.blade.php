{{-- Consumers list body filter --}}
<div class="row g-1">
    <div class="col-auto">
        <select name="connection_type_id" id="connection_type_id" class="form-select">
            <option value="">All Connection Types</option>
            <option value="1" @selected(1 == request()->connection_type_id)>Postpaid</option>
            <option value="2" @selected(2 == request()->connection_type_id)>Prepaid</option>
        </select>
    </div>
    <div class="col-auto">
        <div class="form-control d-flex align-items-center">
            <div class="form-check form-switch m-0">
                <input
                    class="form-check-input"
                    type="checkbox"
                    role="switch"
                    id="register_status"
                    name="register_status"
                    value="1"
                    @checked(request()->register_status == "1")
                >
                <label class="form-check-label ms-2" for="register_status">
                    Self Registered
                </label>
            </div>
        </div>
    </div>
    <div class="col-auto">
        @if (request()->has('geo_area'))
            <div class="form-control">
                Charge Area<x-master.charge-area-filter class="float-end"/>
            </div>
        @endif
    </div>
    <div class="col-auto">
        @if (request()->has('charge_area'))
            <div class="form-control">
                Area<x-master.area-filter class="float-end"/>
            </div>
        @endif
    </div>
</div>