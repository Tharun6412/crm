{{-- Credit Note generation --}}

@extends('layouts.layout')

@section('title', "Credit Note")

@section('page-title', 'Credit Note')

@section('page-content')
    <div class="container-fluid">
        <div class="border p-2 rounded">
        <x-consumer.invoice-details :invoice="$invoice" class="bg-info-subtle mt-1"/>
        @if ($invoice->status_id == 1)
            <div class="alert alert-danger text-center">
                Invoice is fully paid. Credit or debit note cannot be issued. Please contact the administrator.
            </div>
        @else
            <div id="credit-note-create-success">
                <form action="{{ url('bill/creditNote/create/' . $invoice->id) }}" id="credit-note-create-form">
                    @csrf
                    <div class="d-flex justify-content-between mb-1 p-1">
                        <div class="fs-5 fw-semibold">Credit Note items</div>
                        <div>
                            <select name="note_type" id="note_type" class="form-select">
                                <option value="">Select Type</option>
                                    <option value="1">Credit Note</option>
                                    <option value="2">Debit Note</option>
                            </select>
                        </div>
                    </div>
                    <table class="table table-bordered table-hover">
                        <thead class="table-success">
                            <tr>
                                <th width="1%" nowrap>#</th>
                                <th>Description</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end">Quantity</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody id="credit-note-body">
                            <tr>
                                <td>
                                    <button type="button" class="btn btn-outline-danger btn-sm del-row"><i class="bi bi-trash"></i></button>
                                </td>
                                <td>
                                    <textarea name="description[]" rows="1" class="form-control"></textarea>
                                </td>
                                <td>
                                    <input type="text" name="price[]" class="form-control text-end price">
                                </td>
                                <td>
                                    <input type="text" name="qty[]" class="form-control text-end qty"></div>
                                </td>
                                <td class="text-end"><span class="total">0.00</span></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" rowspan="3" class="text-center">
                                    <button type="button" class="btn btn-outline-info btn-sm" id="add-credit-row"><i class="bi bi-plus-lg"></i>&nbsp;Add Row</button>
                                </td>
                                <td colspan="2" class="text-end fw-bold">Total</td>
                                <td class="text-end fw-bold"><span id="cr-total"></span></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="input-group">
                                        <label for="tax_id" class="input-group-text">Tax</label>
                                        <select name="tax_id" id="tax_id" class="form-select">
                                            <option value="">Select Tax</option>
                                            @foreach ($taxes as $tax)
                                                <option value="{{ $tax->id }}">{{ $tax->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" name="tax_value" id="tax_value" class="form-control">
                                        <label for="tax_value" class="input-group-text">%</label>
                                    </div>
                                </td>
                                <td class="text-end"><span id="tax_amount"></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-end bg-body-secondary fw-bold">Credit Note Total</td>
                                <td class="bg-body-secondary text-end fw-bold"><span id="tax-total"></span></td>
                            </tr>
                        </tfoot>
                    </table>
                    <div id="credit-note-create-error"></div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i>&nbsp;Save</button>
                    </div>
                </form>
            </div>
        @endif
        <p>Notes: </p>
        <div>
            <div class="fs-5 fw-semibold mb-2">Credit/ Debit Notes ({{ $credit_notes->count() }})</div>
            @if ($credit_notes->count() > 0)
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th width="1%" nowrap>S No</th>
                            <th>Type</th>
                            <th>Code</th>
                            <th>Date</th>
                            <th class="text-end">Amount</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($credit_notes as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ ($item->type == 1) ? 'Credit' : 'Debit' }} Note</td>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->created_at?->format('d-m-Y H:i') }}</td>
                                <td class="text-end">{{ numberFormat($item->total_amount, 2) }}</td>
                                <td>{{ $item->createdBy->emp_id }}</td>
                                <td>
                                    <a href="{{ url('bill/creditNote/' . $item->id) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye">&nbsp;</i>View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-warning">
                    No credit / debit note data found!
                </div>
            @endif
        </div>
    </div>
    </div>
@endsection()
{{-- Scripts --}}
@include('scripts.ajax-form-submit', ['form' => 'credit-note-create'])
@push('scripts')
    <script type="module">
        $(function(){
            // Calculations
            $("#credit-note-create-form table tbody").on('change', '.price, .qty', function(e) {
                let price = $.isNumeric($(this).closest('tr').find('input.price').val()) ? parseFloat($(this).closest('tr').find('input.price').val()) : 0;
                let qty = $.isNumeric($(this).closest('tr').find('input.qty').val()) ? parseFloat($(this).closest('tr').find('input.qty').val()) : 0;
                $(this).closest('tr').find('span.total').html((price * qty).toFixed(2));
                creditTotal();
            });
            // TAX calculations
            $("#tax_value").on('change', function(e) {
                 taxTotal();
            });
            // Add row
            $("#add-credit-row").on('click', function(e) {
                $("#credit-note-create-form table tbody").append('<tr><td><button type="button" class="btn btn-outline-danger btn-sm del-row"><i class="bi bi-trash"></i></button></td><td><textarea name="description[]" rows="1" class="form-control"></textarea></td><td><input type="text" name="price[]" class="form-control text-end price"></td><td><input type="text" name="qty[]" class="form-control text-end qty"></div></td><td class="text-end"><span class="total">0.00</span></td></tr>');
            });
            // Remove row
            $("#credit-note-create-form table tbody").on('click', '.del-row', function(e) {
                e.target.closest('tr').remove();
            });
        });
    </script>
    <script>
        function creditTotal() {
            var cr_total = 0;
            $(".total").each(function(e){
                let pt = $.isNumeric($(this).html()) ? parseFloat($(this).html()) : 0;
                cr_total += pt;
            });
            $("#cr-total").html(cr_total.toFixed(2));
            taxTotal();
        }
        function taxTotal() {
            let total = $.isNumeric($("#cr-total").html()) ? parseFloat($("#cr-total").html()) : 0;
            let tax = $.isNumeric($("#tax_value").val()) ? parseFloat($("#tax_value").val()) : 0;
            var tax_amount = 0, $cr_total = 0;
            tax_amount = (total * (tax / 100));
            $("#tax_amount").html(tax_amount.toFixed(2));
            $("#tax-total").html((total + tax_amount).toFixed(2));
        }
    </script>
@endpush