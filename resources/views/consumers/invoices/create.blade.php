@extends('layouts.layout')
@section('title', "Invoice Generation")

@section('page-titla', 'Invoice Generation')

@section('page-content')
    <div id="add-invoice-success">
        <form action="{{ url('bill/invoice/') }}" name="add-invoice-form" id="add-invoice-form" method="post">
            @csrf
            <input type="hidden" id="id" name="id" value="{{ $consumer->id }}">
            {{-- Consumer basic details --}}
            <x-consumer.basic-details :consumer="$consumer" :type="0" class="bg-info-subtle" />
            <div class="card">
                <div class="row g-2 pb-2 mb-2">
                    <div class="col-sm-3">
                        <label class="form-label">Invoice Type</label>
                        <select name="invoice_type" id="invoice_type" class="form-select" onchange="getInvoiceItems(this.value)">
                            <option value="">Select Invoice Type</option>
                            @foreach ($invoice_item_types as $type )
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label">Invoice Item</label>
                        <select name="item" id="item" class="form-select">
                            <option value="">Select Item</option>
                            @foreach ($invoice_items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label">Quantity</label>
                        <input type="text" class="form-control" name="quantity" id="quantity" placeholder="Please Enter Quantity">
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button class="btn btn-success btn-sm" type="button" onclick="addItem()"><i class="bi bi-plus-square">&nbsp;</i>Add</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="inv-error"></div>
            <div class="card mb-2 d-none" id="inv-body">
            </div>
        </form>
    </div>
    <script>
        function getInvoiceItems(type)
        {
            $.get("{{ url('bill/invoice/invoiceItems') }}", {'invoice_type' : type}, function(data) {
                $('#item').empty();
                let options = '<option value="">Select</option>';
                if(data.items && data.items.length > 0) {
                    data.items.forEach(function(value){
                        options += '<option value="'+value.id+'">'+value.name+'</option><span id="item-price" ds-price="'+value.price+'"></span>';
                    });
                }
                $('#item').html(options);
            });
        }

        function addItem()
        {
            $('#inv-body').removeClass('d-none');
            var invoice_item_type = $('#invoice_type').val();
            var item = $('#item').val();
            var quantity = $('#quantity').val();
            $.get("{{ url('bill/invoice/renderTax') }}", {'item':item, 'quantity':quantity}, function(data) {
                $('#inv-body').html(data);
                $('#inv-error').html('');
            }).fail(function(data){
                $('#inv-body').html('');
                $('#inv-error').html('<div class="alert alert-danger mb-0">' + data.responseJSON.message + '</div>');
            });
        }
    </script>
@endsection