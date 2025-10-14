{{-- Module item recursive element --}}

@foreach ($child_modules as $module)
    <li>
        @if ($module->recursiveChilds->isNotEmpty())
            <span class="toggle"><i class="bi bi-chevron-down"></i></span>
        @else
            <span><i class="bi bi-dot"></i></span>
        @endif
        <span><i class="bi {{ $module->icon }}"></i>&nbsp;{{ $module->name }}</span>
        {{-- Actions buttons --}}
        <a href="{{ url('admin/modules/' . $module->id . '/edit') }}" class="btn btn-outline-info btn-sm link-modal">
            <i class="bi bi-pencil"></i>&nbsp;Edit
        </a>
        <button type="button" class="btn btn-sm {{ ($module->status == 1) ? 'btn-outline-warning' : 'btn-outline-success' }}">
            <i class="bi bi-{{ ($module->status == 1) ? 'x-lg' : 'check-lg' }}"></i>
            {{ ($module->status == 1) ? 'Disable' : 'Enable' }}
        </button>
        <button type="button" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-trash"></i>&nbsp;Delete
        </button>
        <a href="{{ url('admin/modules/createSub/' . $module->id) }}" class="btn btn-outline-primary btn-sm link-modal">
            <i class="bi bi-plus"></i>Add Sub Module
        </a>
        {{-- Recursive display with view --}}
        @if ($module->recursiveChilds->isNotEmpty())
            <ul>
                @include('admin.modules.module-item', ['child_modules' => $module->recursiveChilds])
            </ul>
        @endif
    </li>
@endforeach