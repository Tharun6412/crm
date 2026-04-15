{{-- Consumer Document, create --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Add new document</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div>
                <h3>File uploader</h3>
                <div id="dc-file-uploader">
                    <form id="dc-file-uploader-form" action="{{ url('consumers/consumerDocument/'.$id) }}" enctype="multipart/form-data" method="post">
                        @csrf
                        @method('PUT')
                        @isset($add_session)
                            <input type="hidden" name="add_session" value="{{ $add_session }}">
                        @endisset
                        <div class="row mb-2">
                            <div class="col-auto">
                                <select name="dc_type" id="dc_type" class="form-select form-select">
                                    <option value="">Select file type</option>
                                    @isset($document_types)
                                        @foreach ($document_types as $type)
                                            <option value="{{ $type->id}}">{{ $type->name }}</option>
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
                    </form>
                    <div id="dc-file-uploader-msg" class="mt-3"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/dc/dc.js') }}"></script>