{{-- Document centre, Browser --}}

<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Document Browser</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-browse-tab" data-bs-toggle="tab" data-bs-target="#nav-browse" type="button" role="tab" aria-controls="nav-browse" aria-selected="true"><i class="bi bi-folder2-open"></i>&nbsp;Browse</button>
                    <button class="nav-link" id="nav-upload-tab" data-bs-toggle="tab" data-bs-target="#nav-upload" type="button" role="tab" aria-controls="nav-upload" aria-selected="false"><i class="bi bi-cloud-upload"></i>&nbsp;Upload</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active border border-top-0 p-3" id="nav-browse" role="tabpanel" aria-labelledby="nav-browse-tab" tabindex="0">
                    @include('dc.browser.file_browser')
                </div>
                <div class="tab-pane fade border border-top-0 p-3" id="nav-upload" role="tabpanel" aria-labelledby="nav-upload-tab" tabindex="0">
                    @include('dc.browser.file_uploader')
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>