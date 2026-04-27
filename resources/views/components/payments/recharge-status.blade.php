{{-- Recharge status --}}
<span class="badge text-bg-{{ (($status == 2) ? 'success' : 'danger') }}">
    {{ (($status == 2) ? 'Sent' : 'Not-Sent') }}
</span>