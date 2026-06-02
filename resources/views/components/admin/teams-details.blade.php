{{-- Team Details --}}
@props([
    'teams' => [],
])
<div class="row g-2 pb-2 my-2 p-2 bg-primary-subtle rounded">
    <div class="col-sm-2 text-end fw-semibold">Team Name : </div>
    <div class="col-sm-4">{{ $teams->name ?? '' }}</div>
    <div class="col-sm-2 text-end fw-semibold">Team Status : </div>
    <div class="col-sm-4">
    @if($teams->status == 1)
        <span class="badge bg-success">Active</span>
    @else
        <span class="badge bg-danger">Inactive</span>
    @endif
    </div>
    <div class="col-sm-2 text-end fw-semibold">Geo Area : </div>
    <div class="col-sm-4">{{ $teams->ga->name ?? ''}}</div>
    <div class="col-sm-2 text-end fw-semibold">Employees : </div>
    <div class="col-sm-4">{{ $teams->users->count() }}</div>
    <div class="col-sm-2 text-end fw-semibold">Created Date : </div>
    <div class="col-sm-4">{{ dateFormat($teams->created_at ?? '') }}</div>
    <div class="col-sm-2 text-end fw-semibold">Created By : </div>
    <div class="col-sm-4">{{ $teams->createdBy->name ?? '' }}</div>
</div>