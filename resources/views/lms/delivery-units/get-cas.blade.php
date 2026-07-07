{{-- Proposal list GA filter --}}
<label for="du_incharge_id" class="col-sm-2 col-form-label text-end">DU Incharge :</label>
<div class="col-sm-4">
    <select name="du_incharge_id" id="du_incharge_id" class="form-select">
        <option value="">Select</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}">{{ $user?->name }} - {{ $user?->emp_id }}</option>
        @endforeach
    </select>
</div>
<div class="dropdown col-sm-4">
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('ca_id')) ? '-fill' : '' }}"></i>&nbsp;&nbsp;Charge Area
        @isset(request()->ca_id)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ sizeof(request()->get('ca_id')) }}
            </span>
        @endisset
    </button>
    <ul class="dropdown-menu bg-light" style="max-height: 300px; overflow-y: auto;">
        @php
            $charge_area_checked = (request()->has('ca_id')) ? request()->get('ca_id') : [];
        @endphp
        @foreach ($charge_areas as $item)
            <li class="dropdown-item">
                <input type="checkbox" class="form-check-input ca_id_filter" name="ca_id[{{ $item->id }}]" id="ca_id_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $charge_area_checked))>
                <label for="ca_id_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
            </li>
        @endforeach
    </ul>
</div>
@include('scripts.checkall-filter', ['element' => 'ca_id'])
<script type="text/javascript">
    $(document).on('change', '.ca_id_filter',function() {
        let ca_ids = $('.ca_id_filter:checked').map(function () {
            return $(this).val();
        }).get();
        getAreasByCa(ca_ids);
        function getAreasByCa(ca_ids)
        {
            $.get("{{ url('lms/deliveryUnits/getCaAreas') }}", {
                ca_id: ca_ids
            }, function(response) {
                let html = '';
                if(response.charge_areas && response.charge_areas.length > 0) {
                    response.charge_areas.forEach(function(ca) {
                        html += `
                            <div class="mb-3">
                                <h6 class="fw-bold text-primary border-bottom pb-2">
                                    ${ca.name}
                                </h6>
                                <div class="row">
                        `;
                        response.areas.forEach(function(area) {
                            if(area.ca_id == ca.id) {
                                html += `
                                    <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                                        <div class="form-check">
    
                                            <input class="form-check-input"
                                                type="checkbox"
                                                name="area_id[]"
                                                value="${area.id}"
                                                id="area_${area.id}">
    
                                            <label class="form-check-label" for="area_${area.id}">
                                                ${area.name}
                                            </label>
                                        </div>
                                    </div>
                                `;
                            }
                        });
                        html += `
                                </div>
                            </div>
                        `;
                    });
    
                } else {
                    html = `<span class="text-danger">No Areas Found</span>`;
                }
                $('#area_id').html(html);
            });
        }
    })
</script>