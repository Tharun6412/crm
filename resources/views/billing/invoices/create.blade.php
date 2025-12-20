{{-- Invoice generation --}}
@extends('layouts.layout')

@section('title', "Generate Invoice")

@section('page-title', 'Generate Invoice')

@section('page-content')
    <div class="container">
        <x-consumer.basic-details :consumer="$consumer" :type="0" class="bg-info-subtle" />
        <div class="" id="add-items">
            <form action="{{ url('bill/invoice/addItem') }}" method="POST" id="inv-add-item-form">
                @csrf
                <div class="p-2 my-2 bg-light rounded">
                    <div class="row g-2 justify-content-center">
                        <div class="col-auto">
                            <select name="type_id" id="type_id" class="form-select">
                                <option value="">Select Item Type</option>
                                @foreach ($item_types as $type )
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <select name="item_id" id="item_id" class="form-select">
                                <option value="">Select Item</option>
                                @foreach ($invoice_items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <input type="text" class="form-control" name="quantity" id="quantity" placeholder="Please Enter Quantity">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-success" type="submit"><i class="bi bi-arrow-right-square">&nbsp;</i>Add Item</button>
                        </div>
                    </div>
                </div>
                <div id="inv-add-item-resp" class="mb-2"></div>
            </form>
        </div>
        {{-- Invoice items body --}}
        <form action="{{ url('bill/invoice/' . $consumer->id) }}" name="invoice-create-form" id="invoice-create-form" method="post">
            <div class="d-flex justify-content-between mb-1">
                <div class="fs-5 fw-semibold">Invoice items</div>
                <div>
                    <select name="invoice_type" id="invoice_type" class="form-select">
                        <option value="">Select Invoice Types</option>
                        @foreach ($invoice_types as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div id="invoice-create-success"></div>
        </form>
    </div>
@endsection
{{-- Script --}}
@include('scripts.ajax-form-submit', ['form' => 'invoice-create'])
@push('scripts')
    <script type="module">
        $(function(){
            // Load invoice items with body
            invoiceBody();
            // Load invoice items
            $("#type_id").on('change', function(e) {
                $.get("{{ url('bill/invoice/typeItems') }}", {'type_id' : e.target.value}, function(data) {
                $('#item_id').empty();
                let options = '<option value="">Select Item Type</option>';
                if(data.items && data.items.length > 0) {
                    data.items.forEach(function(value){
                        options += '<option value="'+value.id+'">'+value.name+'</option><span id="item-price" ds-price="'+value.price+'"></span>';
                    });
                }
                $('#item_id').html(options);
                });
            });
            // Add items to invoice
            $("#inv-add-item-form").on('submit', function(e) {
                e.preventDefault();
                $('#inv-add-item-resp').html('');
                $.post($(this).attr('action'), $(this).serializeArray(), function(response) {
                    $('#inv-add-item-resp').html('<div class="alert alert-success mb-0">' + response.success + '</div>');
                    invoiceBody();
                    e.target.reset();
                }).fail(function(response){
                    $('#inv-add-item-resp').html('<div class="alert alert-danger mb-0">' + response.responseJSON.message + '</div>');
                });
            });
        })
    </script>
    <script>
        function invoiceBody() {
            $.get("{{ url('bill/invoice/createBody') }}", function(data) {
                $("#invoice-create-success").html(data);
            })
        }
    </script>
@endpush