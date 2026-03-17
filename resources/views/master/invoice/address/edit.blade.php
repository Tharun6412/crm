{{-- Edit invoice address form --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5">Edit Invoice Address #{{ $address->ga->name ?? '' }}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="inv-addr-edit-success">
                <form action="{{ url('master/invoice/addresses/' . $address->id) }}" method="POST" id="inv-addr-edit-form">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <label for="ga_id" class="col-sm-3 col-form-label text-end">GA&nbsp;:</label>
                        <label for="ga_id" class="col-sm-8 col-form-label"> {{ $address->ga->name }}</label>
                    </div>
                    <div class="row mb-2">
                        <label for="line1" class="col-sm-3 col-form-label text-end">Address Line 1&nbsp;:</label>
                        <div class="col-sm-8">
                            <input type="text" name="line1" id="line1" class="form-control" value="{{ $address->line1 }}">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="line2" class="col-sm-3 col-form-label text-end">Address Line 2&nbsp;:</label>
                        <div class="col-sm-8">
                            <input type="text" name="line2" id="line2" class="form-control" value="{{ $address->line2 }}">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="city" class="col-sm-3 col-form-label text-end">City / Town&nbsp;:</label>
                        <div class="col-sm-8">
                            <input type="text" name="city" id="city" class="form-control" value="{{ $address->city }}">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="state_id" class="col-sm-3 col-form-label text-end">State&nbsp;:</label>
                        <label for="ga_id" class="col-sm-8 col-form-label"> {{ $address->state->name }}</label>
                    </div>
                    <div class="row mb-2">
                        <label for="district_id" class="col-sm-3 col-form-label text-end">District&nbsp;:</label>
                        <div class="col-sm-8">
                            <select name="district_id" id="district_id" class="form-select">
                                <option value="">Select District</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}"  @selected($district->id == $address->district_id)>{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="pincode" class="col-sm-3 col-form-label text-end">Pincode&nbsp;:</label>
                        <div class="col-sm-8">
                            <input type="text" name="pincode" id="pincode" class="form-control" value="{{ $address->pincode }}">
                        </div>
                    </div>
                    <div id="inv-addr-edit-error" class="text-danger"></div>
                    <div class="row">
                        <div class="offset-sm-3 col-sm-8">
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
@include('scripts.ajax-form-submit', ['form' => 'inv-addr-edit'])
