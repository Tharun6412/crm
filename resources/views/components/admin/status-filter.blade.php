{{-- Custom status filter dropdown --}}

<div class="dropdown float-end">
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has($name)) ? '-fill' : '' }}"></i>
        @isset(request()->$name)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get($name)) }}
        </span>
        @endisset
    </button>
    <ul class="dropdown-menu bg-light">
        @php
            $data_checked = (request()->has($name)) ? request()->get($name) : [];
        @endphp
        @foreach ($data as $id => $value)
            <li class="dropdown-item">
                <input type="checkbox" class="form-check-input" name="{{ $name }}[{{ $id }}]" id="{{ $name }}_{{ $id }}" value="{{ $id }}" @checked(in_array($id, $data_checked))>
                <label for="{{ $name }}_{{ $id }}" class="form-check-label">{{ $value }}</label>
            </li>
        @endforeach
    </ul>
</div>