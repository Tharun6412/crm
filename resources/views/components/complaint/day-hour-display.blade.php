@props([
    'complaint' => null, 
])

@php
    $now = \Carbon\Carbon::now();
    $estimated = \Carbon\Carbon::parse($complaint->estimated_closed_at);

    if ($complaint->category->resolution_type == 1) {
        $days = abs($now->diffInDays($estimated));
        $difference = ceil($days) . 'D';
    } else {
        $hours = abs($now->diffInHours($estimated)); // numeric only

        if ($hours > 24) {
            $difference = ceil($hours / 24) . 'D';
        } else {
            $difference = numberFormat($hours, 2) . 'H';
        }
    }
@endphp
{{-- No display of timezones if the complaint is closed --}}
@if ($complaint->status_id != 5)
    @if ($now > $complaint->estimated_closed_at)
        <span class="badge text-bg-danger">{{ "Expired " . $difference }}</span>
    @else
        <span class="badge text-bg-success">{{ "Expires in " . $difference }}</span>
    @endif
@endif
