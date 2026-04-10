{{-- Navigation item --}}

@foreach ($child_modules as $module)
    <li class="side-nav-item">
        @if ($module->recursiveActiveChilds->isNotEmpty())
            <a href="#nav{{ $module->id }}" class="side-nav-link collapsed" data-bs-toggle="collapse" aria-controls="nav{{ $module->id }}"><i class="bi {{ $module->icon }}"></i>&nbsp;{{ $module->name }}</a>
            <div class="collapse" id="nav{{ $module->id }}">
                {{-- Third level --}}
                <ul class="side-nav-third-level">
                    @foreach ($module->recursiveActiveChilds as $module)
                        <li class="side-nav-item"><a href="{{ url($module->url ?? '') }}"><i class="bi {{ $module->icon }}"></i>&nbsp;{{ $module->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        @else
            <a href="{{ url($module->url ?? '') }}"><i class="bi {{ $module->icon }}"></i>&nbsp;{{ $module->name }}</a>
        @endif
    </li>
@endforeach