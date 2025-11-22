{{-- Show role details --}}

<div class="offcanvas-header bg-light">
    <h4 class="offcanvas-title">Role details</h4>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body">
    <div class="row gy-2 mb-3">
        <div class="col-6">Name:</div>
        <div class="col-6">{{ $role->name }}</div>
        <div class="col-6">Status:</div>
        <div class="col-6">
            <span class="btn btn-outline-{{ ($role->status == 1) ? 'success' : 'warning' }} btn-sm">
                {{ ($role->status == 1) ? 'Enabled' : 'Disabled' }}
            </span>
        </div>
    </div>
    @if (in_array($role->id, [1, 2]))
        <div class="alert alert-success mb-0">Full access!</div>
    @else
        <div class="fw-semibold mb-2"><i class="bi bi-window"></i>&nbsp;Web Modules</div>
        <ol class="list-group list-group-numbered mb-3">
            <li class="list-group-item">Web Module 1</li>
            <li class="list-group-item">Web Module 2</li>
            <li class="list-group-item">Web Module 3</li>
        </ol>
        <div class="fw-semibold mb-2"><i class="bi bi-phone"></i>&nbsp;Mobile App Modules</div>
        @if ($role->appModules)
            <ol class="list-group list-group-numbered">
                @foreach ($role->appModules as $app_module)
                    <li class="list-group-item">{{ $app_module->name }} ({{ $app_module->code }})</li>
                @endforeach
            </ol>
        @else
            
        @endif
    @endif
</div>
