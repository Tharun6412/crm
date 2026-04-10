@props([
    'complaint' => null, 
])

@php
    $now = ($complaint->closed_at) ? $complaint->closed_at : \Carbon\Carbon::now();
    $estimated = \Carbon\Carbon::parse($complaint->estimated_closed_at);

    if ($complaint?->category?->resolution_type == 1) {
        $days = abs($now->diffInDays($estimated));
        $difference = ceil($days) . ' days';
    } else {
        $hours = abs($now->diffInHours($estimated)); // numeric only
        if ($hours > 24) {
            $difference = ceil($hours / 24) . ' days';
        } else {
            $difference = numberFormat($hours, 2) . ' hrs';
        }
    }
@endphp

{{-- No display of timezones if the complaint is closed --}}
@if ($now > $complaint?->estimated_closed_at)
    <span class="text-danger">{{ $difference }}</span>
@else
    <span class="text-success">0</span>
@endif
