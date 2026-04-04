{{-- Consumers list actions --}}
@php
    // Enums Consumer Status 
    use \App\Enums\ConsumerStatus;
@endphp
<div class="dropdown">
    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
    </button>
    <ul class="dropdown-menu">
        <li><x-auth.link class="dropdown-item" href="{{ url('consumers/' . $consumer->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;View</x-auth.link></li>
        {{-- Registration Status Dropdown--}}
        @if ($consumer->status_id == ConsumerStatus::PRE_REGISTER->value)
            <x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/trPayment/' . $consumer->id . '/edit') }}" action="pdpst">
                <i class="bi bi-info-circle"></i>&nbsp;Pay Deposit
            </x-auth.link>
        @endif
        @if ($consumer->status_id == ConsumerStatus::REGISTER->value)
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/accept/'.$consumer->id.'/edit') }}" action="acpt"><i class="bi bi-chevron-right"></i>&nbsp;Acceptance</x-auth.link></li>
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/payDeposit/'.$consumer->id.'/edit') }}" action="psdpst"><i class="bi bi-chevron-right"></i>&nbsp;Pay Deposit</x-auth.link></li>
        @endif
        @if ($consumer->status_id == ConsumerStatus::ACCEPT->value)
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/execute/'.$consumer->id.'/edit') }}" action="exect"><i class="bi bi-chevron-right"></i>&nbsp;Execute</x-auth.link></li>
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/payDeposit/'.$consumer->id.'/edit') }}" action="psdpst"><i class="bi bi-chevron-right"></i>&nbsp;Pay Deposit</x-auth.link></li>
        @endif
        @if ($consumer->status_id == ConsumerStatus::EXECUTE->value)
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/hsconnect/'.$consumer->id.'/edit') }}" action="hsc"><i class="bi bi-chevron-right"></i>&nbsp;HSC</x-auth.link></li>
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/payDeposit/'.$consumer->id.'/edit') }}" action="psdpst"><i class="bi bi-chevron-right"></i>&nbsp;Pay Deposit</x-auth.link></li>
        @endif
        @if ($consumer->status_id == ConsumerStatus::HSC->value)
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/activate/'.$consumer->id.'/edit') }}" action="actvt"><i class="bi bi-chevron-right"></i>&nbsp;Activate</x-auth.link></li>
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/payDeposit/'.$consumer->id.'/edit') }}" action="psdpst"><i class="bi bi-chevron-right"></i>&nbsp;Pay Deposit</x-auth.link></li>
        @endif
        @if ($consumer->status_id == ConsumerStatus::ACTIVATE->value)
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/payDeposit/'.$consumer->id.'/edit') }}" action="psdpst"><i class="bi bi-chevron-right"></i>&nbsp;Pay Deposit</x-auth.link></li>
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/meterChange/'.$consumer->id.'/edit') }}" action="mtrchng"><i class="bi bi-chevron-right"></i>&nbsp;New Meter Change</x-auth.link></li>
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/tdisconnect/'.$consumer->id.'/edit') }}" action="td"><i class="bi bi-chevron-right"></i>&nbsp;Temporary Disconnect</x-auth.link></li>
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/pdisconnect/'.$consumer->id.'/edit') }}" action="pd"><i class="bi bi-chevron-right"></i>&nbsp;Permanent Disconnect</x-auth.link></li>
        @endif
        @if ($consumer->status_id == ConsumerStatus::TD->value)
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/reconnect/'.$consumer->id.'/edit') }}" action="rcnct"><i class="bi bi-chevron-right"></i>&nbsp;Reconnect</x-auth.link></li>
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/pdisconnect/'.$consumer->id.'/edit') }}" action="pd"><i class="bi bi-chevron-right"></i>&nbsp;Permanent Disconnect</x-auth.link></li>
        @endif
        @if ($consumer->status_id == ConsumerStatus::PD->value)
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/refunds/refundRequest/'.$consumer->id) }}" action="refin"><i class="bi bi-chevron-right"></i>&nbsp;Initiate Refund</x-auth.link></li>
        @endif
        @if (in_array($consumer->status_id, [ConsumerStatus::HSC->value, ConsumerStatus::ACTIVATE->value], true) and $consumer->connection_type_id == 2 and $consumer->prepaidData->hes_status != 1) 
            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/prepaid/sendToHes/'.$consumer->id) }}" action="shes"><i class="bi bi-chevron-right"></i>&nbsp;Send To HES</x-auth.link></li>            
        @endif
    </ul>
</div>