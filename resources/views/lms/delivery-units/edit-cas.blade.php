{{-- Proposal list GA filter --}}
<div class="dropdown col-sm-4">
    <button type="button" class="btn btn-link btn-sm p-0"
        data-bs-toggle="dropdown"
        aria-expanded="false"
        data-bs-auto-close="outside">

        <i class="bi bi-funnel{{ request()->has('ca_id') ? '-fill' : '' }}"></i>
        &nbsp;&nbsp;Charge Area
    </button>

    <ul class="dropdown-menu bg-light" style="max-height:300px; overflow-y:auto;">

        @php
            $ca_checked = request()->has('ca_id')
                ? array_values(request()->get('ca_id'))
                : $delivery_unit->areas->pluck('ca_id')->unique()->toArray();
        @endphp

        <li class="dropdown-item">
            <input type="checkbox" class="form-check-input" id="ca_id_all">
            <label for="ca_id_all" class="form-check-label">
                All or clear
            </label>
        </li>

        @foreach ($charge_areas as $item)
            <li class="dropdown-item">
                <input type="checkbox"
                    class="form-check-input ca_id_filter"
                    name="ca_id[]"
                    id="ca_id_{{ $item->id }}"
                    value="{{ $item->id }}"
                    @checked(in_array($item->id, $ca_checked))>

                <label for="ca_id_{{ $item->id }}" class="form-check-label">
                    {{ $item->name }}
                </label>
            </li>
        @endforeach

    </ul>
</div>

@include('scripts.checkall-filter', ['element' => 'ca_id'])


<script type="text/javascript">

$(document).on('change', '.ca_id_filter', function () {

    let ca_ids = $('.ca_id_filter:checked').map(function () {
        return $(this).val();
    }).get();

    getAreasByCa(ca_ids);

});


function getAreasByCa(ca_ids)
{
    $.get("{{ url('lms/deliveryUnits/getEditCaAreas') }}", {
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

                                    <label class="form-check-label"
                                        for="area_${area.id}">
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

</script>