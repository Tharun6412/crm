{{-- Consumer status display --}}
@php
    switch ($status->id) {
        case 1:
            $class = 'secondary';
            break;
        case 2:
            $class = 'primary';
            break;
        case 3:
            $class = 'info';
            break;
        case 4:
            $class = 'dark';
            break;
        case 5:
            $class = 'secondary';
            break;
        case 6:
            $class = 'success';
            break;
        case 7:
            $class = 'warning';
            break;
        case 8:
            $class = 'danger';
            break;

        default:
            $class = 'secondary';
            break;
    }
@endphp
<span class="badge text-bg-{{ $class }}">{{ $status->name ?? '' }}</span>