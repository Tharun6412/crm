{{-- User status --}}

@php
    switch($status->id) {
        case 1: $cls = 'success'; break;
        case 2: $cls = 'danger'; break;
        case 3: $cls = 'warning'; break;
        default: $cls = 'secondary'; break;
    }
@endphp
<span class="badge text-bg-{{ $cls }} w-100">
    {{ $status->name }}
</span>