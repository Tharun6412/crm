{{-- File uploader, sub view in browser.browse --}}

<div>
    <h3>File uploader</h3>
    <div id="dc-file-uploader">
        <form id="dc-file-uploader-form" action="{{ url('master/dc/documents') }}" enctype="multipart/form-data">
            @csrf
            @isset($add_session)
                <input type="hidden" name="add_session" value="{{ $add_session }}">
            @endisset
            <div class="row mb-2">
                <div class="col-auto">
                    <select name="dc_type" id="dc_type" class="form-select form-select">
                        <option value="">Select file type</option>
                        @isset($dc_types)
                            @foreach ($dc_types as $item)
                                <option value="{{ $item->id}}">{{ $item->name }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div class="col-auto">
                    <input type="file" name="file" id="file" class="form-control form-control">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-success"><i class="bi bi-cloud-upload"></i>&nbsp;Upload</button>
                </div>
                <div class="col">
                    <div class="progress mt-2">
                        <div id="progress-bar" class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-auto">
                    <div class="form-floating">
                        <input type="text" name="tag" class="form-control" id="tag" placeholder="tag">
                        <label for="floatingInput">Tag</label>
                    </div>
                    <div class="form-text">
                        To identify this document from Document Centre.
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-floating">
                        <input type="text" name="description" class="form-control" id="description" placeholder="description">
                        <label for="floatingInput">Description</label>
                    </div>
                    <div class="form-text">
                        Anything you want to add regarding this document.
                    </div>
                </div>
            </div>
        </form>
        <div id="dc-file-uploader-msg" class="mt-3"></div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/dc/dc.js') }}"></script>