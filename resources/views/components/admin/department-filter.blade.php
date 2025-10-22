{{-- Department filter dropdown --}}
<div class="dropdown float-end">
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('departments')) ? '-fill' : '' }}"></i>
        @isset(request()->departments)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('departments')) }}
        </span>
        @endisset
    </button>
    <ul class="dropdown-menu bg-light">
        @php
            $departments_checked = (request()->has('departments')) ? request()->get('departments') : [];
        @endphp
        @foreach ($departments as $item)
            <li class="dropdown-item">
                <input type="checkbox" class="form-check-input" name="departments[{{ $item->id }}]" id="department_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $departments_checked))>
                <label for="department_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
            </li>
        @endforeach
    </ul>
</div>