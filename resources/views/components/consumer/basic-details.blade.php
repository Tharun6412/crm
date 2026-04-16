{{-- Consumer basic details --}}
{{-- @type 0:Nothing, 1:SD Schemes, 2:SD payment, 3:Meter Details --}}

@props([
    'consumer' => [],
    'type' => 0,
])

<div>
    {{-- Consumer details --}}
    <div {{ $attributes->merge(['class' => 'rounded mb-2 p-2']) }}>
        <div class="row g-2 pb-2 mb-2 p-2">
            <div class="col-sm-2 text-end fw-semibold">CRN : </div>
            <div class="col-sm-4"><x-auth.link href="{{ url('consumers/'.$consumer->id) }}" target="_blank">{{ $consumer->crn }}</x-auth.link></div>
            <div class="col-sm-2 text-end fw-semibold">Status : </div>
            <div class="col-sm-4"><x-consumer.status :status="$consumer->status" /></div>
            <div class="col-sm-2 text-end fw-semibold">Name : </div>
            <div class="col-sm-4">{{ $consumer->name }}</div>
            <div class="col-sm-2 text-end fw-semibold">Segment : </div>
            <div class="col-sm-4">{{ $consumer->segment->name ?? '' }}</div>
            <div class="col-sm-2 text-end fw-semibold">District : </div>
            <div class="col-sm-4">{{ $consumer->district->name }}</div>
            <div class="col-sm-2 text-end fw-semibold">GA : </div>
            <div class="col-sm-4">{{ $consumer->ga->name }} ({{ $consumer->ga->code }})</div>
            <div class="col-sm-2 text-end fw-semibold">Activated At : </div>
            <div class="col-sm-4">{{ $consumer->statusHistory()->where('status_id', \App\Enums\ConsumerStatus::ACTIVATE->value)->first()?->created_at->format('d-m-Y H:i:s') }}</div>
        </div>
    </div>     
    {{-- Scheme details --}}
    @if ($type == 1 AND $consumer->segment_id != 3)
        <div class="p-2 bg-warning-subtle mt-2 mb-1 rounded">
            <table class="table table-borderless table-info table-sm">
                <tr>
                    <td><span class="fw-semibold">TR No :</span>&nbsp;{{ $consumer->t_crn }}</td>
                    <td><span class="fw-semibold">Scheme :</span>&nbsp;{{ $consumer->scheme->scheme->name }}</td>
                </tr>
                <tr>
                    <td><span class="fw-semibold">Connection :</span>&nbsp;{{ numberFormat($consumer->scheme->scheme->security) }}</td>
                    <td><span class="fw-semibold">Consumption :</span>&nbsp;{{ numberFormat($consumer->scheme->scheme->consumption) }}</td>
                </tr>
                <tr>
                    <td><span class="fw-semibold">Registration :</span>&nbsp;{{ numberFormat($consumer->scheme->scheme->registration) }}</td>
                </tr>
            </table>
        </div> 
    @elseif ($type == 1 AND $consumer->segment_id == 3) 
        <div class="p-2 bg-warning-subtle mt-2 mb-1 rounded">
            <table class="table table-borderless table-info table-sm">
                <tr>
                    <td><span class="fw-semibold">TR No :</span>&nbsp;{{ $consumer->t_crn }}</td>
                    <td><span class="fw-semibold">SD Amount :</span>&nbsp;{{ numberFormat($consumer->scheme->security_deposit) }}</td>
                </tr>
                <tr>
                    <td><span class="fw-semibold">Consumption :</span>&nbsp;{{ numberFormat($consumer->scheme->consumption_deposit) }}</td>
                    <td><span class="fw-semibold">Total Deposit :</span>&nbsp;{{ numberFormat($consumer->scheme->total_deposit) }}</td>
                </tr>
            </table>
        </div>  
    @endif
    {{-- SD details --}}
    @if ($type == 2)
        <div class="p-2 bg-warning-subtle mt-2 mb-2 rounded">
            <table class="table table-borderless table-info table-sm">
                <tr>
                    <td><span class="fw-semibold">Scheme :</span>&nbsp;{{ $consumer->scheme->scheme->name ?? "Industrial" }}</td>
                    <td><span class="fw-semibold">Deposit :</span>&nbsp;{{ numberFormat($consumer->scheme->total_deposit) }}</td>
                </tr>
                <tr>
                    <td><span class="fw-semibold">Paid :</span>&nbsp;{{ numberFormat($consumer->scheme->paid_deposit) }}</td>
                    <td><span class="fw-semibold">Balance :</span>&nbsp;{{ numberFormat($consumer->scheme->balance) }}</td>
                </tr>
            </table>
        </div>
    @endif

    {{-- Bill details --}}
    @if ($type == 3)
        <div class="p-2 bg-warning-subtle mt-2 mb-2 rounded">
            <table class="table table-borderless table-info table-sm">
                <tr>
                    <td><span class="fw-semibold">Meter No :</span>&nbsp;{{ $consumer->activeMeter->meter_no }}</td>
                    <td><span class="fw-semibold">Initial Reading :</span>&nbsp;{{ $consumer->activeMeter->initial_reading }}</td>
                </tr>
            </table>
        </div>
    @endif
</div>