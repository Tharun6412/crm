{{-- Create price form --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Create New Price</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="cns-price-create-success">
                <form action="{{ url('master/consumer/prices') }}" method="POST" id="cns-price-create-form">
                    @csrf
                    <div id="cns-price-create-error" class="text-danger"></div>
                    <button type="submit" class="btn btn-success"><i class="bi bi-plus-lg"></i>&nbsp;Create</button>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
{{-- Scripts --}}
@include('scripts.ajax-form-submit', ['form' => 'cns-price-create'])