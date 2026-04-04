{{-- Invoice status --}}
{{-- <span>{{ $status->name }}</span> --}}

@props(['status' => null, 'mode' => null])

@php
    switch ($status->id) {
        case 1:
            $class = 'primary';
            $icon = 'hourglass-split';
            break;
        case 2:
            $class = 'info';
            $icon = 'hand-thumbs-up';
            break;
        case 3:
            $class = 'warning';
            $icon = 'file-check';
            break;
        case 4:
            $class = 'success';
            $icon = 'check2-square';
            break;
            case 5:
            $class = 'secondary';
            $icon = 'person-raised-hand';
            break;            
            case 6:
            $class = 'danger';
            $icon = 'x-circle';
            break;                       
            case 7:
            $class = 'danger';
            $icon = 'x-square';
            break;                                   
            case 8:
            $class = 'info';
            $icon = 'door-closed';
            break;
        default:
            $class = 'secondary';
            $icon = 'fan';
            break;
    }
@endphp
<span class="btn btn-sm btn-outline-{{ $class }} {{ ($mode =='full') ? 'w-100' : '' }}"><i class="bi bi-{{ $icon }}"></i>&nbsp;{{ $status->name ?? '' }}</span>