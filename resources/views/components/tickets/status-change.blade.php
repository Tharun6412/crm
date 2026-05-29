@props(['status' => null, 'mode' => null])

@php
    switch ($status->id) {

        case 1: // Register
            $class = 'info';
            $icon = 'person-square';
            break;

        case 2: // Approve
            $class = 'primary';
            $icon = 'check2-circle';
            break;

        case 3: // Processing
            $class = 'warning';
            $icon = 'gear-wide-connected';
            break;

        case 4: // Hold
            $class = 'secondary';
            $icon = 'pause-circle';
            break;

        case 5: // Close
            $class = 'success';
            $icon = 'check2-square';
            break;

        case 6: // Cancel
            $class = 'danger';
            $icon = 'x-circle';
            break;

        default:
            $class = 'dark';
            $icon = 'question-circle';
            break;
    }
@endphp
<span class="btn btn-sm btn-outline-{{ $class }} {{ ($mode =='full') ? 'w-100' : '' }}"><i class="bi bi-{{ $icon }}"></i>&nbsp;{{ $status->name ?? '' }}</span>