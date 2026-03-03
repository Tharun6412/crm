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
        @if (request()->has('geo_area'))
            <div class="form-control form-control-sm">
                Charge Area<x-master.charge-area-filter class="float-end"/>
            </div>
        @endif
    </div>
    <div class="col-auto">
        @if (request()->has('charge_area'))
            <div class="form-control form-control-sm">
                Area<x-master.area-filter class="float-end"/>
            </div>
        @endif
    </div>
</div>