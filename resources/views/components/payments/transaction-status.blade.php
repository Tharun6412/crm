{{-- Transaction status --}}
<span class="badge text-bg-{{ ($status->id == 1) ? 'warning' : (($status->id == 2) ? 'success' : 'danger') }}">
    {{ $status->name }}
</span>