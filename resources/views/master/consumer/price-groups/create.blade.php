{{-- Create price group form --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Create New Price Group</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="price-group-create-success">
                <form action="{{ url('master/consumer/price-groups') }}" method="POST" id="price-group-create-form">
                    @csrf
                    <div class="row mb-2">
                        <label for="code" class="col-sm-2 col-form-label text-end">Code</label>
                        <div class="col-sm-9">
                            <input type="text" name="code" id="code" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="description" class="col-sm-2 col-form-label text-end">Description</label>
                        <div class="col-sm-9">
                            <input type="text" name="description" id="description" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="segment_id" class="col-sm-2 col-form-label text-end">Segment</label>
                        <div class="col-sm-9">
                            <select name="segment_id" id="segment_id" class="form-select">
                                <option value="">Select Segment</option>
                                @foreach ($segments as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="ga_id" class="col-sm-2 col-form-label text-end">Geo Area</label>
                        <div class="col-sm-9">
                            <select name="ga_id" id="ga_id" class="form-select">
                                <option value="">Select Geo Area</option>
                                @foreach ($geo_areas as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label for="prce-details" class="col-sm-2 col-form-label text-end">Price Data</label>
                        <div class="col-sm-9">
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label for="basic" class="form-label">Basic(<i class="bi bi-currency-rupee"></i>)</label>
                                    <input type="text" name="basic" class="form-control text-end" id="basic">
                                </div>
                                <div class="col-sm-4">
                                    <label for="vat" class="col-form-label">VAT(%)</label>
                                    <input type="text" name="vat" id="vat" class="form-control text-end">
                                </div>
                                <div class="col-sm-4">
                                    <label for="rsp" class="col-form-label">RSP(<i class="bi bi-currency-rupee"></i>)</label>
                                    <input type="text" name="rsp" id="rsp" class="form-control text-end" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="effective_from" class="col-sm-2 col-form-label text-end">Effective from</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" name="effective_from" id="effective_from" class="form-control" placeholder="DD-MM-YYYY">
                                <label for="effective_from" class="input-group-text"><i class="bi bi-calendar3"></i></label>
                            </div>
                        </div>
                    </div>
                    <div id="price-group-create-error" class="text-danger"></div>
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
@include('scripts.ajax-form-submit', ['form' => 'price-group-create'])
@include('scripts.datepicker', ['list' => ['effective_from']])
<script type="module">
    $(function(){
        // Price calculations
        $("#basic, #vat").on('change', function(e) {
            let basic = $.isNumeric($("#basic").val().trim()) ? parseFloat($("#basic").val().trim()) : 0;
            let vat = $.isNumeric($("#vat").val().trim()) ? parseFloat($("#vat").val().trim()) : 0;
            $("#rsp").val((basic + (basic * vat) / 100).toFixed(2));
        });
    });
</script>