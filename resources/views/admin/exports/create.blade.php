<div class="modal-dialog modal-lg">
    <div class="modal-content shadow-sm border-0">
        <div class="modal-header">
            <h4 class="modal-title"><i class="bi bi-filetype-csv me-2"></i>&nbsp;Export in Progress</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center py-4">
            <div class="p-4 border rounded shadow-sm bg-light">
                <div class="mb-3"><i class="bi bi-hourglass-split text-warning fs-4"></i></div>
                @if ($export_id)
                    <h6 class="fw-semibold mb-2">Your export request has been successfully submitted.</h6>
                    <p class="text-muted mb-3">
                        You will be able to download the file once the export process is completed. Please check the status and access your exported files using the link below.
                    </p>
                @else
                    <p class="text-danger mb-3">
                        You can export a maximum of 3 files at a time. Once the current export process is completed, you may proceed with exporting additional files.
                    </p>
                @endif
                <a href="{{ url('user/exports') }}" class="btn btn-primary" target="_blank">
                    <i class="bi bi-folder2-open me-1"></i> Go to Exports
                </a>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i> Close</button>
        </div>
    </div>
</div>