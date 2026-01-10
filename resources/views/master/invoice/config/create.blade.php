{{-- Create invoice numbering form --}}

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Create Invoice Numbering</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="inv-number-success">
                <form action="{{ url('master/invoice/configuration') }}" method="POST" id="inv-number-form">
                    @csrf
                    <div class="row mb-2">
                        <label for="state_id" class="col-sm-2 col-form-label text-end">State</label>
                        <div class="col-sm-9">
                            <select name="state_id" id="state_id" class="form-select">
                                <option value="">Select State</option>
                                @foreach ($states as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="tax_group_id" class="col-sm-2 col-form-label text-end">Tax Group</label>
                        <div class="col-sm-9">
                            <select name="tax_group_id" id="tax_group_id" class="form-select">
                                <option value="">Select Tax Group</option>
                                @foreach ($tax_groups as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="invoice_code" class="col-sm-2 col-form-label text-end">Invoice Code</label>
                        <div class="col-sm-9">
                            <input type="text" name="invoice_code" id="invoice_code" class="form-control">
                        </div>
                    </div>
                    <div id="inv-number-error" class="text-danger"></div>
                    <div class="row">
                        <div class="offset-sm-2 col-sm-9">
                            <button type="submit" class="btn btn-success"><i class="bi bi-plus-lg"></i>&nbsp;Create</button>
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
@include('scripts.ajax-form-submit', ['form' => 'inv-number'])