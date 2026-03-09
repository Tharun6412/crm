{{-- Create price form --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Create New Price</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="cns-price-create-success">
                <form action="{{ url('master/consumer/prices') }}" method="POST" id="cns-price-create-form">
                    @csrf
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
                    <div class="row mb-2">
                        <label for="district_id" class="col-sm-2 col-form-label text-end">District</label>
                        <div class="col-sm-9">
                            <select name="district_id" id="district_id" class="form-select">
                                <option value="">Select District</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label for="prce-details" class="col-sm-2 col-form-label text-end mt-3">Price Data</label>
                        <div class="col-sm-9">
                            <div class="row p-1 mb-2 bg-body-tertiary">
                                <div class="col-sm-4">
                                    <label for="basic" class="form-label">Basic(<i class="bi bi-currency-rupee"></i>)</label>
                                    <input type="text" name="basic" class="form-control text-end" id="basic" placeholder="Basic Price">
                                </div>
                                <div class="col-sm-4">
                                    <label for="supply" class="form-label">Supply(<i class="bi bi-currency-rupee"></i>)</label>
                                    <input type="text" name="supply" class="form-control text-end" id="supply" placeholder="Supply Price">
                                </div>
                                <div class="col-sm-4">
                                    <label for="margin" class="form-label">Margin(<i class="bi bi-currency-rupee"></i>)</label>
                                    <input type="text" name="margin" class="form-control text-end" id="margin" placeholder="Margin Price">
                                </div>
                            </div>
                            <div class="row p-1 mb-2 bg-body-tertiary">
                                <div class="col-sm-4">
                                    <label for="price" class="col-form-label">Price(<i class="bi bi-currency-rupee"></i>)</label>
                                    <input type="text" name="price" id="price" class="form-control text-end" disabled>
                                </div>
                                <div class="col-sm-4">
                                    <label for="vat" class="col-form-label">VAT(%)</label>
                                    <input type="text" name="vat" id="vat" class="form-control text-end" placeholder="VAT Percentage">
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
                    <div id="cns-price-create-error" class="text-danger m-2"></div>
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
@include('scripts.ajax-form-submit', ['form' => 'cns-price-create'])
@include('scripts.datepicker', ['list' => ['effective_from']])
<script type="module">
    $(function(){
        // Choose GA for districts
        $("#ga_id").on('change', function(e) {
            $.get("{{ url('common/gaDistricts') }}", {'ga_id': e.target.value}, function(response){
                let options = '<option value = "">Select district</option>';
                if(response.districts && response.districts.length > 0) {
                    response.districts.forEach(function(dist) {
                        options += `<option value="${dist.id}">${dist.name}</option>`;
                    });
                }
                $('#district_id').html(options);
            })
        });
        // Price calculations
        $("#basic, #supply, #margin, #vat").on('change', function(e) {
            let basic = $.isNumeric($("#basic").val().trim()) ? parseFloat($("#basic").val().trim()) : 0;
            let supply = $.isNumeric($("#supply").val().trim()) ? parseFloat($("#supply").val().trim()) : 0;
            let margin = $.isNumeric($("#margin").val().trim()) ? parseFloat($("#margin").val().trim()) : 0;
            let vat = $.isNumeric($("#vat").val().trim()) ? parseFloat($("#vat").val().trim()) : 0;
            let price = basic + supply + margin;
            $("#price").val(price.toFixed(2));
            $("#rsp").val((price + (price * vat) / 100).toFixed(2));
        });
    });
</script>