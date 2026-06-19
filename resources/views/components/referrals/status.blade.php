{{-- Consumer status display --}}
@if ($status)
    <span class="badge text-bg-success"><i class="bi bi-patch-check">&nbsp;</i>Earned</span>
@else
    <span class="badge text-bg-warning"><i class="bi bi-hourglass-split">&nbsp;</i>Processing</span>    
@endif