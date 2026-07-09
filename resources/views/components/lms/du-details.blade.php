{{-- Team Details --}}
@props([
    'du' => [],
])
<div class="row g-2 pb-2 my-2 p-2 bg-primary-subtle rounded">
    <div class="col-sm-2 text-end fw-semibold">Delivery Unit Name : </div>
    <div class="col-sm-4">{{ $du->name ?? '' }}</div>
    <div class="col-sm-2 text-end fw-semibold">Status : </div>
    <div class="col-sm-4">
    @if($du->status == 1)
        <span class="badge bg-success">Active</span>
    @else
        <span class="badge bg-danger">Inactive</span>
    @endif
    </div>
    <div class="col-sm-2 text-end fw-semibold">Geo Area : </div>
    <div class="col-sm-4">{{ $du->ga->name ?? ''}}</div>
    <div class="col-sm-2 text-end fw-semibold">Delivery Manager : </div>
    <div class="col-sm-4">{{ $du->duIncharge?->name }}</div>
    <div class="col-sm-2 text-end fw-semibold">Department : </div>
    <div class="col-sm-4">{{ $du->department?->name }}</div>
    <div class="col-sm-2 text-end fw-semibold">Teams Assigned : </div>
    <div class="col-sm-4">{{ $du->teams->count() }}</div>
    <div class="col-sm-2 text-end fw-semibold">Responsible Status : </div>
    <div class="col-sm-4">{{ $du->responsibleStatus?->name }}</div>
    <div class="col-sm-2 text-end fw-semibold">Action Status : </div>
    <div class="col-sm-4">{{ $du->actionStatus?->name }}</div>
    <div class="col-sm-2 text-end fw-semibold">Created Date : </div>
    <div class="col-sm-4">{{ dateFormat($du->created_at ?? '') }}</div>
    <div class="col-sm-2 text-end fw-semibold">Created By : </div>
    <div class="col-sm-4">{{ $du->createdBy?->name ?? '' }}</div>
</div>