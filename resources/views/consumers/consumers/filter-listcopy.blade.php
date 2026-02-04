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

                {{-- Charge Area --}}
                <div class="row my-3">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-light btn-sm p-2" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                            <i class="bi bi-funnel{{ (request()->has('charge_area')) ? '-fill' : '' }}"></i>Charge Area
                            <span class="badge rounded-pill bg-danger ms-1 charge-area-count">
                                {{ request()->has('charge_area') ? sizeof(request()->get('charge_area')) : 0 }}
                            </span>
                        </button>
                        <div class="dropdown-menu data-filter">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <input type="checkbox" class="form-check-input" id="charge_area_all">
                                    <label for="charge_area_all" class="form-check-label">All or clear</label>
                                </li>
                                @php
                                    $charge_area_checked = request()->get('charge_area', []);
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

                {{-- Area --}}
                <div class="row my-3">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-light btn-sm p-2" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                            <i class="bi bi-funnel{{ (request()->has('area')) ? '-fill' : '' }}"></i>Area
                            <span class="badge rounded-pill bg-danger ms-1 area-count">
                                {{ request()->has('area') ? sizeof(request()->get('area')) : 0 }}
                            </span>
                        </button>
                        <div class="dropdown-menu data-filter">
                            <ul class="list-group list-group-flush" id="area_list">
                                <li class="list-group-item">
                                    <input type="checkbox" class="form-check-input" id="area_all">
                                    <label for="area_all" class="form-check-label">All or clear</label>
                                </li>
                                {{-- Area checkboxes will be dynamically loaded here --}}
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
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

<script type="text/javascript">
$(document).ready(function() {

    // ---------- Charge Area "All or Clear" ----------
    $('#charge_area_all').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.charge_area_filter').prop('checked', isChecked);
        updateChargeAreaBadge();
        loadAreas(); // reload areas based on selected charge areas
    });

    // Update badge when individual charge areas change
    $(document).on('change', '.charge_area_filter', function() {
        const total = $('.charge_area_filter').length;
        const checked = $('.charge_area_filter:checked').length;
        $('#charge_area_all').prop('checked', total === checked);
        updateChargeAreaBadge();
        loadAreas();
    });

    // ---------- Area "All or Clear" ----------
    $(document).on('change', '#area_all', function() {
        const isChecked = $(this).is(':checked');
        $('.area_filter').prop('checked', isChecked);
        updateAreaBadge();
    });

    // Update badge when individual areas change
    $(document).on('change', '.area_filter', function() {
        const total = $('.area_filter').length;
        const checked = $('.area_filter:checked').length;
        $('#area_all').prop('checked', total === checked);
        updateAreaBadge();
    });

    // ---------- Load Areas Dynamically ----------
    function loadAreas() {
        let selectedChargeAreas = [];
        $('.charge_area_filter:checked').each(function() {
            selectedChargeAreas.push($(this).val());
        });

        const areaList = $('#area_list');

        // Clear previous areas except "All"
        areaList.find('li:not(:first)').remove();

        if(selectedChargeAreas.length === 0) {
            updateAreaBadge();
            return;
        }

        // Get previously selected areas from server-side
        let preSelectedAreas = Object.values(@json(request()->get('area', []))).map(String);
        // AJAX to fetch areas
        $.get("{{ url('consumers/filters/areaByCA') }}", { charge_area: selectedChargeAreas }, function(data) {
            data.forEach(function(area) {
                let isChecked = preSelectedAreas.includes(area.id.toString()) ? 'checked' : '';
                areaList.append(`
                    <li class="list-group-item">
                        <input type="checkbox" class="form-check-input area_filter" name="area[${area.id}]" id="area_${area.id}" value="${area.id}" ${isChecked}>
                        <label for="area_${area.id}" class="form-check-label">${area.name}</label>
                    </li>
                `);
            });

            // Update badges
            updateAreaBadge();

            // Update "All or Clear" checkbox
            const total = $('.area_filter').length;
            const checked = $('.area_filter:checked').length;
            $('#area_all').prop('checked', total === checked);
        });
    }

    function updateAreaBadge() {
        const count = $('.area_filter:checked').length;
        $('.area-count').text(count);
    }

    // ---------- Badge Updates ----------
    function updateChargeAreaBadge() {
        const count = $('.charge_area_filter:checked').length;
        $('.charge-area-count').text(count);
    }
    // ---------- Initial Load ----------
    loadAreas(); // in case some charge areas are pre-selected
});
</script>
