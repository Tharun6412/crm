{{-- Module item recursive element --}}

@foreach ($child_modules as $module)
    <li>
        @if ($module->recursiveChilds->isNotEmpty())
            <span class="toggle"><i class="bi bi-chevron-down"></i></span>
        @else
            <span><i class="bi bi-dot"></i></span>
        @endif
        <label>
            <span><i class="bi {{ $module->icon }}"></i>&nbsp;{{ $module->name }}</span>&nbsp;<i class="bi bi-arrow-right"></i>&nbsp;
        </label>
        @if ($module->moduleActions->count() > 0)
            <div class="row mb-0 g-1 p-2 bg-light">
                @foreach ($module->moduleActions as $action)
                    <div class="col-4 fs-sm">
                        <input type="checkbox" name="rights[{{ $action->id }}]" id="right_{{ $action->id }}" class="form-check-input" value="{{ $action->id }}" @checked(in_array($action->id, $rights))>
                        <label for="right_{{ $action->id }}" class="text-primary">{{ $action->action }} ({{ $action->slug }})</label>
                    </div>
                @endforeach
            </div>
        @else
            <span class="badge text-bg-light">No actions defined!</span>
        @endif
        
        {{-- Recursive display with view --}}
        @if ($module->recursiveChilds->isNotEmpty())
            <ul>
                @include('admin.roles.role-module-item', ['child_modules' => $module->recursiveChilds])
            </ul>
        @endif
    </li>
@endforeach