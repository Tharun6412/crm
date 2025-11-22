{{-- Navigation item --}}

@foreach ($child_modules as $module)
    <li class="side-nav-item">
        <a href="{{ url($module->url ?? '') }}"><i class="bi {{ $module->icon }}"></i>&nbsp;{{ $module->name }}</a>
        @if ($module->recursiveActiveChilds->isNotEmpty())
            <div class="collapse" id="nav{{ $module->id }}">
                {{-- Third level --}}
                <ul class="side-nav-third-level">
                    @foreach ($module->recursiveActiveChilds as $module)
                        <li class="side-nav-item"><a href="{{ url($module->url ?? '') }}"><i class="bi {{ $module->icon }}"></i>&nbsp;{{ $module->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        @endif
    </li>
@endforeach