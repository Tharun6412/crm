{{-- Complaint User filter --}}

<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('user_id')) ? '-fill' : '' }}"></i>
        @isset(request()->user_id)            
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ sizeof(request()->get('user_id')) }}
            </span>
        @endisset
    </button>
    <div class="dropdown-menu data-filter">
        <ul class="list-group list-group-flush">
            @php
                $user_list_checked = (request()->has('user_id')) ? request()->get('user_id') : [];
            @endphp
            @foreach ($user_list as $list)
                <li class="list-group-item">
                    <input type="checkbox" class="form-check-input" name="user_id[{{ $list->id }}]" id="user_id_{{ $list->id }}" value="{{ $list->id }}" @checked(in_array($list->id, $user_list_checked))>
                    <label for="user_id_{{ $list->id }}" class="form-check-label">{{ $list->name }}&nbsp;({{ $list->emp_id }})</label>
                </li>
            @endforeach
        </ul>
    </div>
</div>