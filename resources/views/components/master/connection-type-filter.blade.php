{{-- Segments filter --}}
<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('connection_type_id')) ? '-fill' : '' }} fs-6 text-primary"></i>
        @isset(request()->connection_type_id)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('connection_type_id')) }}
        </span>
        @endisset
    </button>
    <div class="dropdown-menu data-filter">
        <ul class="list-group list-group-flush">
            @php
                $type_checked = (request()->has('connection_type_id')) ? request()->get('connection_type_id') : [];
            @endphp
            <li class="list-group-item">
                <input type="checkbox" class="form-check-input connection_type_id-filter" name="connection_type_id[1]" id="connection_type_id_1" value="1" @checked(in_array(1, $type_checked))>
                <label for="connection_type_id_1" class="form-check-label">Postpaid</label>
            </li>
            <li class="list-group-item">
                <input type="checkbox" class="form-check-input connection_type_id-filter" name="connection_type_id[2]" id="connection_type_id_2" value="2" @checked(in_array(2, $type_checked))>
                <label for="connection_type_id_2" class="form-check-label">Prepaid</label>
            </li>
        </ul>
    </div>
</div>