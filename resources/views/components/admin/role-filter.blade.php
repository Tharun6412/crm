{{-- Role filter dropdown --}}
<div class="dropdown float-end">
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('roles')) ? '-fill' : '' }}"></i>
        @isset(request()->roles)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('roles')) }}
        </span>
        @endisset
    </button>
    <ul class="dropdown-menu bg-light">
        @php
            $roles_checked = (request()->has('roles')) ? request()->get('roles') : [];
        @endphp
        @foreach ($roles as $item)
            <li class="dropdown-item">
                <input type="checkbox" class="form-check-input" name="roles[{{ $item->id }}]" id="role_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $roles_checked))>
                <label for="role_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
            </li>
        @endforeach
    </ul>
</div>