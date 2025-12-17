{{-- Consumers list actions --}}
<div class="dropdown">
    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
    </button>
    <ul class="dropdown-menu">
        <li><x-auth.link class="dropdown-item" href="{{ url('consumers/' . $consumer->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;View</x-auth.link></li>
        {{-- Registration Status Dropdown--}}
        @if ($consumer->status_id == 2)
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/accept/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Acceptance</x-auth.link></li>
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/payDeposit/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Pay Deposit</x-auth.link></li>
        @endif
        @if ($consumer->status_id == 3)
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/execute/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Execute</x-auth.link></li>
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/payDeposit/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Pay Deposit</x-auth.link></li>
        @endif
        @if ($consumer->status_id == 4)
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/hsconnect/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;HSC</x-auth.link></li>
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/payDeposit/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Pay Deposit</x-auth.link></li>
        @endif
        @if ($consumer->status_id == 5)
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/activate/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Activate</x-auth.link></li>
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/payDeposit/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Pay Deposit</x-auth.link></li>
        @endif
        @if ($consumer->status_id == 6)
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/payDeposit/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Pay Deposit</x-auth.link></li>
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/tdisconnect/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Temporary Disconnect</x-auth.link></li>
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/pdisconnect/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Permanent Disconnect</x-auth.link></li>
        @endif
        @if ($consumer->status_id == 7)
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/reconnect/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Reconnect</x-auth.link></li>
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/pdisconnect/'.$consumer->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Permanent Disconnect</x-auth.link></li>
        @endif
        @if ($consumer->status_id == 8)
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/refunds/refundRequest/'.$consumer->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;Initiate Refund</x-auth.link></li>
        @endif
    </ul>
</div>