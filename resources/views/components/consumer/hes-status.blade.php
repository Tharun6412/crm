{{-- Consumer status display --}}
@php
    switch ($status) {
        case 1:
            $class = 'success';
            $name = 'sent';
            break;

        default:
            $class = 'danger';
            $name = 'not-sent';
            break;
    }
@endphp
<span class="badge text-bg-{{ $class }}">{{ $name ?? '' }}</span>