@props([
    'leads' => [],
])
<div class="row g-2 pb-2 my-2 p-2 bg-info-subtle rounded">
    <div class="col-sm-2 text-end fw-semibold">Lead Code : </div>
    <div class="col-sm-4">{{ $leads->code ?? '' }}</div>
    <div class="col-sm-2 text-end fw-semibold">Lead Status : </div>
    <div class="col-sm-4"><x-lms.status-change :status="$leads->status" /></div>
    <div class="col-sm-2 text-end fw-semibold">Geo Area : </div>
    <div class="col-sm-4">{{ $leads->ga->name ?? ''}}</div>
    <div class="col-sm-2 text-end fw-semibold">District : </div>
    <div class="col-sm-4">{{ $leads->district->name ?? '' }}</div>
    <div class="col-sm-2 text-end fw-semibold">Charge Area : </div>
    <div class="col-sm-4">{{ $leads->ca->name ?? '' }}</div>
    <div class="col-sm-2 text-end fw-semibold">Area: </div>
    <div class="col-sm-4">{{ $leads->area->name ?? '' }}</div>
    <div class="col-sm-2 text-end fw-semibold">Create Date : </div>
    <div class="col-sm-4">{{ $leads->created_at ?? '' }}</div>
    <div class="col-sm-2 text-end fw-semibold">Create By : </div>
    <div class="col-sm-4">{{ $leads->createdBy->name ?? '' }}</div>
</div>