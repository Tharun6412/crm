{{-- Ticket status filter --}}

<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('status')) ? '-fill' : '' }}"></i>
        @isset(request()->status)            
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ sizeof(request()->get('status')) }}
            </span>
        @endisset
    </button>
    <div class="dropdown-menu data-filter">
        <ul class="list-group list-group-flush">
            @php
                $ticket_status_checked = (request()->has('status')) ? request()->get('status') : [];
            @endphp
            @foreach ($ticket_status as $item)
                <li class="list-group-item">
                    <input type="checkbox" class="form-check-input" name="status[{{ $item->id }}]" id="status_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $ticket_status_checked))>
                    <label for="status_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
                </li>
            @endforeach
        </ul>
    </div>
</div>