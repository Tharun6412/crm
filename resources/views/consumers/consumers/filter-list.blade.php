<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Advance Search</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="advance-search-form" method="GET">
                {{-- Preserve existing filters --}}
                @foreach(request()->except(['page', 'area', 'charge_area']) as $name => $value)
                    @if(is_array($value))
                        @foreach($value as $k => $v)
                            <input type="hidden" name="{{ $name }}[{{ $k }}]" value="{{ $v }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                    @endif
                @endforeach
                {{-- Advance Filters --}}
                <div class="row mt-3">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <label>Connection Type&nbsp;:</label>
                        <select name="connection_type_id" id="connection_type_id" class="form-select">
                            <option value="">Select Type</option>
                            @foreach ($connection_types as $type)
                                <option value="{{ $type->id }}" @selected($type->id == request()->connection_type_id)>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-light btn-sm p-2" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                            <i class="bi bi-funnel{{ (request()->has('charge_area')) ? '-fill' : '' }}"></i>Charge Area
                            @isset(request()->charge_area)
                                <span class="badge rounded-pill bg-danger ms-1">
                                    {{ sizeof(request()->get('charge_area')) }}
                                </span>
                            @endisset
                        </button>
                        <div class="dropdown-menu data-filter">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <input type="checkbox" class="form-check-input charge_area_filter" id="charge_area_all">
                                    <label for="charge_area_all" class="form-check-label">All or clear</label>
                                </li>
                                @php
                                    $charge_area_checked = (request()->has('charge_area')) ? request()->get('charge_area') : [];
                                @endphp
                                @foreach ($charge_areas as $item)
                                    <li class="list-group-item">
                                        <input type="checkbox" class="form-check-input charge_area_filter" name="charge_area[{{ $item->id }}]" id="charge_area_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $charge_area_checked))>
                                        <label for="charge_area_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-light btn-sm p-2" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                            <i class="bi bi-funnel{{ (request()->has('area')) ? '-fill' : '' }}"></i>Area
                            @isset(request()->area)
                                <span class="badge rounded-pill bg-danger ms-1">
                                    {{ sizeof(request()->get('area')) }}
                                </span>
                            @endisset
                        </button>
                        <div class="dropdown-menu data-filter">
                            <ul class="list-group list-group-flush" id="area_list">
                                <li class="list-group-item">
                                    <input type="checkbox" class="form-check-input check-all" id="area_all">
                                    <label for="area_all" class="form-check-label">All or clear</label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12 col-sm-12">
                        <div class="text-end">
                            <button type="button" class="btn btn-success" onclick="consumer_filter(event)">
                                <i class="bi bi-check2-square" aria-hidden="true">&nbsp;</i>search
                            </button>
                            <a type="button" href="{{ url('consumers') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.checkall-filter', ['element' => 'charge_area'])
<script type="text/javascript">
    // To get Areas
    $(document).on('change', '.charge_area_filter', function() {
        // Get all selected Charge Area IDs
        let selectedChargeAreas = [];
        $('.charge_area_filter:checked').each(function() {
            selectedChargeAreas.push($(this).val());
        });
        // Areas
        const areaList = $('#area_list');
        // If nothing selected, clear areas list
        if (selectedChargeAreas.length === 0) {
            areaList.find('li:not(:first)').remove();
            return;
        }
        // Make AJAX request to fetch areas for selected Charge Areas
        $.get("{{ url('consumers/filters/areaByCA') }}", { charge_area: selectedChargeAreas }, function(data) {
            areaList.find('li:not(:first)').remove();
            // Append new area checkboxes
            data.forEach(function(area) {
                areaList.append(`
                    <li class="list-group-item">
                        <input type="checkbox" class="form-check-input area_filter" name="area[${area.id}]" id="area_${area.id}" value="${area.id}">
                        <label for="area_${area.id}" class="form-check-label">${area.name}</label>
                    </li>
                `);
            });
        });
    });
</script>

