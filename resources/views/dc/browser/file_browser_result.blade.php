{{-- Document Centre, file browser, file browser result view  --}}

<div id="dc-fb-select-success">
    @if ($dc_files->count() > 0)
        <div class="p-1 mb-2">
            ({{ $dc_files->count() }}) Top results
            @php
               $session_dc_files = (session()->get('dc_files')) ? array_keys(session()->get('dc_files')) : [];
            @endphp
        </div>
        <form action="{{ url('dc/selectFiles') }}" id="dc-fb-select-form">
            @csrf
            <div class="row row-cols-3 row-gap-3">
                @foreach ($dc_files as $file)
                    <div class="col">
                        <div class="form-check border pt-2 pb-2 {{ (in_array($file->id, $session_dc_files)) ? 'bg-dark-subtle' : '' }}" style="padding-left: 35px;">
                            <input type="checkbox" name="dc_files[{{ $file->id }}]" value="{{ $file->id }}" id="dc_file_{{ $file->id }}" class="form-check-input mt-3 ml-2" {{ (in_array($file->id, $session_dc_files)) ? 'checked disabled' : '' }}>
                            <label for="dc_file_{{ $file->id }}">
                                {{ $file->doc_number }}<br/>
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" style="font-size: 12px">{{ $file->file_name_original }}</a>
                            </label>
                            <i class="bi bi-file-earmark-pdf float-end pe-2 fs-2"></i>
                        </div>
                    </div>
                @endforeach
            </div>
            <div id="dc-fb-select-error"></div>
            <div class="text-center pt-2 mb-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check2-square"></i>&nbsp;Select Documents
                </button>
            </div>
        </form>
    @else
        <x-layouts.callout-info message="No documents found."/>
    @endif
</div>
{{-- Load dynamic scripts --}}
@include('scripts.ajax-form-submit', ['form' => 'dc-fb-select'])