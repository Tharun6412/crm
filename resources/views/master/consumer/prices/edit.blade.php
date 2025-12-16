{{-- Edit price form --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Change Price</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="cns-price-create-success">
                <form action="{{ url('master/consumer/prices/' . $price_details->id) }}" method="POST" id="cns-price-create-form">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <label for="segment_id" class="col-sm-2 col-form-label text-end">Segment</label>
                        <div class="col-sm-9">
                            {{ $price_details->segment->name ?? '' }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="ga_id" class="col-sm-2 col-form-label text-end">Geo Area</label>
                        <div class="col-sm-9">
                            {{ $price_details->district->ga->name ?? '' }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="district_id" class="col-sm-2 col-form-label text-end">District</label>
                        {{ $price_details->district->name ?? '' }}
                    </div>
                    <div class="row">
                        <label for="prce-details" class="col-sm-2 col-form-label text-end">Price Data</label>
                        <div class="col-sm-9">
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label for="basic" class="form-label">Basic(<i class="bi bi-currency-rupee"></i>)</label>
                                    <input type="text" name="basic" class="form-control text-end" id="basic" value="{{ $price_details->basic }}">
                                </div>
                                <div class="col-sm-4">
                                    <label for="supply" class="form-label">Supply(<i class="bi bi-currency-rupee"></i>)</label>
                                    <input type="text" name="supply" class="form-control text-end" id="supply" value="{{ $price_details->supply }}">
                                </div>
                                <div class="col-sm-4">
                                    <label for="margin" class="form-label">Margin(<i class="bi bi-currency-rupee"></i>)</label>
                                    <input type="text" name="margin" class="form-control text-end" id="margin" value="{{ $price_details->margin }}">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <label for="price" class="col-form-label">Price(<i class="bi bi-currency-rupee"></i>)</label>
                                    <input type="text" name="price" id="price" class="form-control text-end" value="{{ $price_details->basic_price }}" disabled>
                                </div>
                                <div class="col-sm-4">
                                    <label for="vat" class="col-form-label">VAT(%)</label>
                                    <input type="text" name="vat" id="vat" class="form-control text-end" value="{{ $price_details->tax_value }}">
                                </div>
                                <div class="col-sm-4">
                                    <label for="rsp" class="col-form-label">RSP(<i class="bi bi-currency-rupee"></i>)</label>
                                    <input type="text" name="rsp" id="rsp" class="form-control text-end" value="{{ $price_details->rsp }}" disabled>
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
                    <div class="row mb-2">
                        <label for="effective_to" class="col-sm-2 col-form-label text-end">Effective to</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" name="effective_to" id="effective_to" class="form-control" placeholder="DD-MM-YYYY">
                                <label for="effective_to" class="input-group-text"><i class="bi bi-calendar3"></i></label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="offset-sm-2 col-sm-9">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="history" id="history">
                                <label class="form-check-label" for="history">
                                    Insert history record
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="cns-price-create-error" class="text-danger"></div>
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
@include('scripts.ajax-form-submit', ['form' => 'cns-price-create'])
@include('scripts.datepicker', ['list' => ['effective_from', 'effective_to']])
<script type="module">
    $(function(){
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