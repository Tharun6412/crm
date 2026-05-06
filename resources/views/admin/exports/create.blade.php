<div class="modal-dialog modal-lg">
    <div class="modal-content shadow-sm border-0">
        <div class="modal-header">
            <h5 class="modal-title fw-semibold text-primary">
                <i class="bi bi-download me-2"></i> Export in Progress
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center py-4">
            <div class="p-4 border rounded shadow-sm bg-light">
                <div class="mb-3">
                    <i class="bi bi-hourglass-split text-warning" style="font-size: 2rem;"></i>
                </div>
                <h6 class="fw-semibold mb-2">Your export has started</h6>
                <p class="text-muted mb-3">
                    Your file is being processed. You can track its status below.
                </p>
                <a href="{{ url('user/exports') }}" class="btn btn-primary" target="_blank">
                    <i class="bi bi-folder2-open me-1"></i> Go to Downloads
                </a>
                <p class="text-muted mt-3 small">
                    Check the status (Pending or Completed) and download your file when it's ready.
                </p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x"></i> Close</button>
        </div>
    </div>
</div>