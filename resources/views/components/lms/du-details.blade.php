{{-- Team Details --}}
@props([
    'du' => [],
])
<div class="row g-2 pb-2 my-2 p-2 bg-primary-subtle rounded">
    <div class="col-sm-2 text-end fw-semibold">Team Name : </div>
    <div class="col-sm-4">{{ $du->name ?? '' }}</div>
    <div class="col-sm-2 text-end fw-semibold">Team Status : </div>
    <div class="col-sm-4">
    @if($du->status == 1)
        <span class="badge bg-success">Active</span>
    @else
        <span class="badge bg-danger">Inactive</span>
    @endif
    </div>
    <div class="col-sm-2 text-end fw-semibold">Geo Area : </div>
    <div class="col-sm-4">{{ $du->ga->name ?? ''}}</div>
    <div class="col-sm-2 text-end fw-semibold">Delivery Unit Incharge : </div>
    <div class="col-sm-4">{{ $du->duIncharge?->name }}</div>
    <div class="col-sm-2 text-end fw-semibold">Created Date : </div>
    <div class="col-sm-4">{{ dateFormat($du->created_at ?? '') }}</div>
    <div class="col-sm-2 text-end fw-semibold">Created By : </div>
    <div class="col-sm-4">{{ $du->createdBy?->name ?? '' }}</div>
</div>