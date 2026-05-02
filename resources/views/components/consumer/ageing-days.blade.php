{{-- Consumer status display --}}

@props(['days' => null, 'mode' => null])

@php
    if ($days === '-') {
        $class = 'secondary';
        $icon  = 'question-circle';
    } elseif ($days <= 30) {
        $class = 'success';
        $icon  = 'person-check';
    } elseif ($days <= 60) {
        $class = 'primary';
        $icon  = 'file-text';
    } elseif ($days <= 90) {
        $class = 'info';
        $icon  = 'check-all';
    } elseif ($days <= 180) {
        $class = 'warning';
        $icon  = 'gear-wide';
    } else {
        $class = 'danger';
        $icon  = 'x-circle';
    }
@endphp
<span class="badge text-bg-{{ $class }} {{ ($mode =='full') ? 'w-100' : '' }}">&nbsp;{{ $days }} days</span>