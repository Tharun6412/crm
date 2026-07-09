{{-- Delivery Manager --}}
<label for="delivery_manager_id" class="col-sm-2 col-form-label text-end">Delivery Manager :</label>
<div class="col-sm-4">
    <select name="delivery_manager_id" id="delivery_manager_id" class="form-select">
        <option value="">Select</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}">{{ $user?->first_name }}&nbsp;{{ $user?->last_name }} - {{ $user?->emp_id }}</option>
        @endforeach
    </select>
</div>
<br/>
<br/>
<hr/>
<div class="row mb-3">
    <div class="col-sm-12">
        <h4>Charge Areas and Areas :</h4>
    </div>
    <div class="col-sm-12">
        <div id="area_id" class="border rounded p-3">
            <div class="col-12">
                @if (count($charge_areas) > 0)
                    @foreach($charge_areas as $ca)
                        <div class="mb-4">
                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                {{ $ca->name }}
                            </h6>
                            <div class="row">
                                @foreach($areas->where('ca_id', $ca->id) as $area)
                                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="area_id[]" value="{{ $area->id }}"
                                                id="area_{{ $area->id }}" @disabled(in_array($area->id, $assigned_areas))>
                                            <label class="form-check-label" for="area_{{ $area->id }}">{{ $area->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <span class="text-muted"> Select Geo Area to get Areas</span>
                @endif
            </div>
        </div>
    </div>
</div>