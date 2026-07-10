{{-- Complaint category view datails --}}

<div class="offcanvas-header bg-light">
    <h4 class="offcanvas-title">Category details</h4>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body">   
    {{-- <div>Name: {{ $category->name }}</div>
    <div>Priority: {{ $category->priority->name ?? '' }}</div>
    <div>Type: {{ $category->type->name ?? '' }}</div>
    <div>Department: {{ $category->department->name ?? '' }}</div>
    <div>Resolution: {{ $category->resolution }} {{ ($category->resolution_type == 1) ? 'Days' : 'Hours' }}</div>
    <div>Created By: {{ $category->createdBy->emp_id ?? '' }}</div>
    <div>Created Date: {{ $category->created_at?->format('d.m.Y H:i') }}</div>
    <div>Last Updated By: {{ $category->updatedBy->emp_id ?? '' }}</div>
    <div>Last Updated  Date: {{ $category->updated_at?->format('d.m.Y H:i') }}</div> --}}
    <table class="table table-bordered table-hover table-striped">
        <tbody>
            <tr>
                <td>Name</td>
                <td>{{ $category->name }}</td>
            </tr>
            <tr>
                <td>Priority</td>
                <td>{{ $category->priority->name ?? '' }}</td>
            </tr>
            <tr>
                <td>Type</td>
                <td>{{ $category->type->name ?? '' }}</td>
            </tr>
            <tr>
                <td>Department</td>
                <td>{{ $category->department->name ?? '' }}</td>
            </tr>
            <tr>
                <td>Resolution</td>
                <td>{{ $category->resolution }} {{ ($category->resolution_type == 1) ? 'Days' : 'Hours' }}</td>
            </tr>
            <tr>
                <td>Created By</td>
                <td>{{ $category->createdBy->emp_id ?? '' }}</td>
            </tr>
            <tr>
                <td>Created Date</td>
                <td>{{ $category->created_at?->format('d.m.Y H:i') }}</td>
            </tr>
            <tr>
                <td>Last Updated By</td>
                <td>{{ $category->updatedBy->emp_id ?? '' }}</td>
            </tr>
            <tr>
                <td>Last Updated  Date</td>
                <td>{{ $category->updated_at?->format('d.m.Y H:i') }}</td>
            </tr>
        </tbody>
    </table>
    <div class="d-flex justify-content-end fs-5">
        <x-common.status :status="$category->status"/>
    </div>
</div>