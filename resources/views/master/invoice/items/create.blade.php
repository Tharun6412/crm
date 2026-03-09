{{-- Create invoice item form --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Create New Item</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="inv-item-create-success">
                <form action="{{ url('master/invoice/items') }}" method="POST" id="inv-item-create-form">
                    @csrf
                    <div class="row mb-2">
                        <label for="type_id" class="col-sm-2 col-form-label text-end">Item Type&nbsp;:</label>
                        <div class="col-sm-9">
                            <select name="type_id" id="type_id" class="form-select">
                                <option value="">Select Item Type</option>
                                @foreach ($item_types as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="code" class="col-sm-2 col-form-label text-end">Code&nbsp;:</label>
                        <div class="col-sm-9">
                            <input type="text" name="code" id="code" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="name" class="col-sm-2 col-form-label text-end">Name&nbsp;:</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="hsn" class="col-sm-2 col-form-label text-end">HSN&nbsp;:</label>
                        <div class="col-sm-9">
                            <input type="text" name="hsn" id="hsn" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="basic" class="col-sm-2 col-form-label text-end">Basic Price&nbsp;:</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" name="basic" id="basic" class="form-control text-end">
                                <label for="basic" class="input-group-text"><i class="bi bi-currency-rupee"></i></label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="tax" class="col-sm-2 col-form-label text-end">Tax(%)&nbsp;:</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" name="tax" id="tax" class="form-control text-end">
                                <label for="tax" class="input-group-text"><i class="bi bi-percent"></i></label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="price" class="col-sm-2 col-form-label text-end">Price&nbsp;:</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" name="price" id="price" class="form-control text-end">
                                <label for="price" class="input-group-text"><i class="bi bi-currency-rupee"></i></label>
                            </div>
                        </div>
                    </div>
                    <div id="inv-item-create-error" class="text-danger p-2"></div>
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
@include('scripts.ajax-form-submit', ['form' => 'inv-item-create'])
<script type="module">
    $(function(){
        $("#basic, #tax, #price").on('change', function(e) {
            let basic = $.isNumeric($("#basic").val().trim()) ? parseFloat($("#basic").val().trim()) : 0;
            let tax = $.isNumeric($("#tax").val().trim()) ? parseFloat($("#tax").val().trim()) : 0;
            let price = $.isNumeric($("#price").val().trim()) ? parseFloat($("#price").val().trim()) : 0;
            var cprice = 0, bprice = 0;
            // Check target and calculate
            if(e.target.id == 'basic' || e.target.id == 'tax') {
                cprice = basic + ((basic * tax) / 100);
                $("#price").val(cprice.toFixed(2));
            }
            if(e.target.id == 'price') {
                bprice = (price / (1 + (tax / 100)));
                $("#basic").val(bprice.toFixed(2));
            }
        });
    });
</script>