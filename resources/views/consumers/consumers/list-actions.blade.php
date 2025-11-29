{{-- Consumers list actions --}}
<div class="dropdown">
    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="{{ url('consumers/' . $consumer->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;view</a></li>
        {{-- Registration Status Dropdown--}}
        @if ($consumer->status_id == 2)
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/registration/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Acceptance</a></li>
        @endif
        @if ($consumer->status_id == 3)
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/execution/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Execute</a></li>
        @endif
        @if ($consumer->status_id == 4)
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/hscAction/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;HSC</a></li>
        @endif
        @if ($consumer->status_id == 5)
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/activation/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Activate</a></li>
        @endif
        @if ($consumer->status_id == 6)
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/temporaryDisconnect/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Temporary Disconnect</a></li>
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/permanentDisconnect/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Permanent Disconnect</a></li>
        @endif
        @if ($consumer->status_id == 7)
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/permanentDisconnect/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Permanent Disconnect</a></li>
        @endif
        @if ($consumer->status_id == 8)
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/refund/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Initiate Refund</a></li>
        @endif
    </ul>
</div>