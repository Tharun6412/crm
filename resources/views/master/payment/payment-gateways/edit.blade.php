{{-- Edit Payment gateway form --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="gw-edit-success">
                <form action="{{ url('master/payment/paymentGateways/' . $gateway->id) }}" method="POST" id="gw-edit-form">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <label for="gateway" class="col-sm-2 col-form-label text-end">Gateway</label>
                        <div class="col-sm-9">
                            <input type="text" name="gateway" id="gateway" class="form-control" value="{{ $gateway->gateway }}">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="status" class="col-sm-2 col-form-label text-end">Status</label>
                        <div class="col-sm-9">
                            <select name="status" id="status" class="form-select">
                                <option value="1" @selected($gateway->is_active == 1)>Enable</option>
                                <option value="0" @selected($gateway->is_active == 0)>Disable</option>
                            </select>
                        </div>
                    </div>
                    @foreach ($gateway->credentials as $key => $value)
                        <div class="row mb-2">
                            <label for="{{ $key }}" class="col-sm-2 col-form-label text-end">{{ $key }}</label>
                            <div class="col-sm-9">
                                <input type="text" name="credentials[{{ $key }}]" id="{{ $key }}" value="{{ $value }}" class="form-control">
                            </div>
                        </div>
                    @endforeach
                    <div id="gw-edit-error" class="text-danger"></div>
                    <div class="row">
                        <div class="offset-sm-2 col-sm-9">
                            <button type="submit" class="btn btn-success"><i class="bi bi-save"></i>&nbsp;Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
{{-- Scripts --}}
@include('scripts.ajax-form-submit', ['form' => 'gw-edit'])
