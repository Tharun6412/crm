{{-- Complaint category view datails --}}

<div class="offcanvas-header bg-light">
    <h4 class="offcanvas-title">Category details</h4>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body">
    <x-common.status :status="$category->status"/>
    <div>Name: {{ $category->name }}</div>
    <div>Priority: {{ $category->priority->name ?? '' }}</div>
    <div>Type: {{ $category->type->name ?? '' }}</div>
    <div>Department: {{ $category->department->name ?? '' }}</div>
    <div>Resolution: {{ $category->resolution }} {{ ($category->resolution_type == 1) ? 'Days' : 'Hours' }}</div>
    <div>Created By: {{ $category->createdBy->emp_id ?? '' }}</div>
    <div>Created Date: {{ $category->created_at?->format('d.m.Y H:i') }}</div>
    <div>Last Updated By: {{ $category->updatedBy->emp_id ?? '' }}</div>
    <div>Last Updated  Date: {{ $category->updated_at?->format('d.m.Y H:i') }}</div>
</div>