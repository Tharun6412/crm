{{-- Consumer basic details --}}
{{-- @type 0:Nothing, 1:SD Schemes, 2:SD payment, 3:Meter Details --}}

@props([
    'consumer' => [],
    'type' => 0,
])

<div {{ $attributes->merge(['class' => 'rounded mb-2']) }}>
    {{-- Consumer details --}}
    <div class="row g-2 pb-2 mb-2">
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
    </div>
    {{-- Scheme details --}}
    @if ($type == 1)
        <div class="row g-2 pb-2 mb-2">
            <div class="col-sm-2 text-end fw-semibold">TR No : </div>
            <div class="col-sm-4">{{ $consumer->t_crn }}</div>
            <div class="col-sm-2 text-end fw-semibold">Scheme : </div>
            <div class="col-sm-4">{{ $consumer->scheme->scheme->name }}</div>
            <div class="col-sm-2 text-end fw-semibold">Connection : </div>
            <div class="col-sm-2">{{ numberFormat($consumer->scheme->scheme->security) }}</div>
            <div class="col-sm-2 text-end fw-semibold">Consumption : </div>
            <div class="col-sm-2">{{ numberFormat($consumer->scheme->scheme->consumption) }}</div>
            <div class="col-sm-2 text-end fw-semibold">Registration : </div>
            <div class="col-sm-2">{{ numberFormat($consumer->scheme->scheme->registration) }}</div>
        </div>
    @endif
    {{-- SD details --}}
    @if ($type == 2)
        <div class="row g-2 pb-2 mb-2">
            <div class="col-sm-2 text-end fw-semibold">Scheme : </div>
            <div class="col-sm-4">{{ $consumer->scheme->scheme->name }}</div>
            <div class="col-sm-2 text-end fw-semibold">Deposit : </div>
            <div class="col-sm-4">{{ numberFormat($consumer->scheme->total_deposit) }}</div>
            <div class="col-sm-2 text-end fw-semibold">Paid : </div>
            <div class="col-sm-4">{{ numberFormat($consumer->scheme->paid_deposit) }}</div>
            <div class="col-sm-2 text-end fw-semibold">Balance : </div>
            <div class="col-sm-4">{{ numberFormat($consumer->scheme->balance) }}</div>
        </div>
    @endif

    {{-- Bill details --}}
    @if ($type == 3)
        <div class="row g-2 pb-2 mb-2">
            <div class="col-sm-2 text-end fw-semibold">Meter No : </div>
            <div class="col-sm-4">{{ $consumer->activeMeter->meter_no }}</div>
            <div class="col-sm-2 text-end fw-semibold">Initial Reading : </div>
            <div class="col-sm-4">{{ $consumer->activeMeter->initial_reading }}</div>
        </div>
    @endif
</div>