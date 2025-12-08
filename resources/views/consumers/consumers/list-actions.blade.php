{{-- Consumers list actions --}}
<div class="dropdown">
    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="{{ url('consumers/' . $consumer->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;View</a></li>
        {{-- Registration Status Dropdown--}}
        @if ($consumer->status_id == 2)
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/accept/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Acceptance</a></li>
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/payDeposit/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Pay Deposit</a></li>
        @endif
        @if ($consumer->status_id == 3)
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/execute/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Execute</a></li>
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/payDeposit/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Pay Deposit</a></li>
        @endif
        @if ($consumer->status_id == 4)
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/hsconnect/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;HSC</a></li>
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/payDeposit/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Pay Deposit</a></li>
        @endif
        @if ($consumer->status_id == 5)
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/activate/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Activate</a></li>
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/payDeposit/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Pay Deposit</a></li>
        @endif
        @if ($consumer->status_id == 6)
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/payDeposit/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Pay Deposit</a></li>
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/tdisconnect/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Temporary Disconnect</a></li>
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/pdisconnect/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Permanent Disconnect</a></li>
        @endif
        @if ($consumer->status_id == 7)
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/reconnect/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Reconnect</a></li>
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/pdisconnect/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Permanent Disconnect</a></li>
        @endif
        @if ($consumer->status_id == 8)
            <li><a class="dropdown-item link-modal" href="{{ url('consumers/refund/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Initiate Refund</a></li>
        @endif
    </ul>
</div>