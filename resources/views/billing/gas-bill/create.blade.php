{{-- Gas invoice generations --}}
@extends('layouts.layout')

@section('title', "Gas Bill")

@section('page-title', 'Gas Invoice Generation')

@section('page-content')
    <div>
        <x-consumer.basic-details :consumer="$consumer" type="3" class="bg-info-subtle"/>
    </div>
    <div id="add-gas-bill-success">
        <form action="{{ url('bill/gasInvoice/') }}" name="add-gas-bill-form" id="add-gas-bill-form" method="post">
            @csrf
            <input type="hidden" id="id" name="id" value="{{ $consumer->id }}">
            <input type="hidden" id="meter_init_reading" name="meter_init_reading" value="{{ $consumer->meter->initial_reading }}">
            <div class="mb-2">
                @php
                    $start_date = (!empty($invoice)) ? $invoice->consumption->last()->date_to->format('Y-m-d') : ($consumer->statusHistory->first()->created_at->format('Y-m-d'));
                    $invEndReading = (!empty($invoice) and $invoice->consumption->count() > 0)
                        ? $invoice->consumption->last()->curr_reading
                        : 0;
                    $startReading = ($invEndReading > 0) ? $invEndReading : (($consumer->meter->initial_reading >= 0) ? $consumer->meter->initial_reading : "");
                @endphp
                @if (!empty($invoice))
                    <div class="bg-warning-subtle rounded mt-3">
                        <input type="hidden" id="inv_end_reading" name="inv_end_reading" value={{ $invEndReading }} >
                        <div class="row g-2 pb-2 mb-2">
                            <div class="col-sm-2 text-end fw-semibold">Invoice No. : </div>
                            <div class="col-sm-4">{{ $invoice->invoice_number }}</div>
                            <div class="col-sm-2 text-end fw-semibold">Status : </div>
                            <div class="col-sm-4"><x-invoice.status :status="$invoice->status"/></div>
                            <div class="col-sm-2 text-end fw-semibold">Invoice Date : </div>
                            <div class="col-sm-4">{{ $invoice->invoice_date->format('d-m-Y') }}</div>
                            <div class="col-sm-2 text-end fw-semibold">Consumption : </div>
                            <div class="col-sm-4">{{ $invoice->consumption->last()->net_consumption ?? '' }} SCM</div>
                            <div class="col-sm-2 text-end fw-semibold">Reading : </div>
                            <div class="col-sm-4">{{ $invEndReading }} - {{ $invoice->consumption->last()->prev_reading }}</div>
                            <div class="col-sm-2 text-end fw-semibold">Amount : </div>
                            <div class="col-sm-4">{{ $invoice->total_amount }}</div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">No Previous Invoices Found..!</div>
                @endif
            </div>
            @if (!empty($consumer->meter->meter_no) AND $startReading >= 0)
                @if ($start_date == date('Y-m-d'))
                    <div class="alert alert-danger">Invoice already generated or consumer activated today.</div>
                @elseif ($bill_days < 10)
                    <div class="alert alert-danger">Billing Frequency should be greater than equal to 10 days.</div>
                @elseif ($prices->isEmpty() OR $prices->last()->basic_price <= 0 OR $prices->last()->tax_value <= 0)
                    <div class="alert alert-danger">No price record found. Please update the price.</div>
                @else
                    <div class="bg-light rounded">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="start_reading" class="col-sm-4 col-form-label text-end">Billing Period&nbsp;:&nbsp;</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label">({{ date('d-m-Y', strtotime($start_date)) }} to {{ date('d-m-Y') }})</label>
                                        <input type="hidden" id="start_date" name="start_date" value="{{ $start_date  }}">
                                        <input type="hidden" id="end_date" name="end_date" value="{{ date('Y-m-d') }}">
                                        <br>{{ $bill_days }} Days
                                    </div>
                                    <label for="start_reading" class="col-sm-4 col-form-label text-end">Previous Reading&nbsp;:&nbsp;</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label">{{ $startReading }}</label>
                                        <input type="hidden" id="start_reading" name="start_reading" value="{{ $startReading }}">
                                    </div>
                                    <label for="end_reading" class="col-sm-4 col-form-label text-end">Current Reading<i class="text-danger">*</i>&nbsp;:&nbsp;</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="end_reading" id="end_reading" placeholder="Current reading" onchange="calculateReadings(this.value)">
                                            <label for="end_reading" class="input-group-text"><i class="bi bi-input-cursor"></i></label>
                                        </div>
                                        <span class="text-danger" id="end_read_err"></span>
                                    </div>
                                    <div class="offset-sm-4 col-sm-8 mt-2">
                                        <button class="btn btn-sm btn-success" type="submit"><i class="bi bi-plus-square">&nbsp;</i>Generate Bill</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="unit_price" class="col-sm-4 col-form-label text-end">Unit Price&nbsp;:&nbsp;</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label">{{ $prices->last()->basic_price }} / SCM</label>
                                        <input type="hidden" id="unit_price" name="unit_price" value="{{ $prices->last()->basic_price }}">
                                    </div>
                                    <label for="vat" class="col-sm-4 col-form-label text-end">VAT&nbsp;:&nbsp;</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label">{{ $prices->last()->tax_value }} %</label>
                                        <input type="hidden" id="tax_price" name="tax_price" value="{{ $prices->last()->tax_value }}">
                                    </div>
                                    <label for="base_amount" class="col-sm-4 col-form-label text-end">Net SCM&nbsp;:&nbsp;</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label" id="net_scm"></label> SCM
                                    </div>
                                    <label for="base_amount" class="col-sm-4 col-form-label text-end">Base Amount&nbsp;:&nbsp;</label>
                                    <div class="col-sm-8">
                                       <label class="col-form-label" id="base_amt"></label>                                    </div>
                                    <label for="tax_amount" class="col-sm-4 col-form-label text-end">VAT Amount&nbsp;:&nbsp;</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label" id="tax_amt"></label>
                                    </div>
                                    <label for="total_amount" class="col-sm-4 col-form-label text-end">Total Amount&nbsp;:&nbsp;</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label" id="total_amt"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="cust_err_msg" id="cust_err_msg">
                    <div id="add-gas-bill-error"></div>
                    
                @endif
            @else
                <div class="alert alert-danger">Please update the Meter number / Initial meter reading.</div>
            @endif
        </form>
    </div>
@endsection()
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-form-submit', ['form' => 'add-gas-bill'])
    <script type="module">
        $(function(){

        });
    </script>
    <script>
        function calculateReadings(reading) {
            var end_read = parseFloat(reading ?? 0);
            if(!isNaN(end_read)){
                var start_read = parseFloat($('#start_reading').val());
                if(end_read < start_read) {
                    $('#end_read_err').html('End reading must be greater than start reading.');
                    $('#net_scm').html("");
                    $('#base_amt').html("");
                    $('#tax_amt').html("");
                    $('#total_amt').html("");
                    return false;
                }
                else {
                    var net_scm = Number(end_read - start_read).toFixed(3);
                    var price = $('#unit_price').val();
                    var vat = $('#tax_price').val();
                    var base_amot = (net_scm * price).toFixed(2);
                    var tax_amot = (parseFloat(base_amot * vat)/100).toFixed(2);
                    var total_amot = Number(parseFloat(base_amot) + parseFloat(tax_amot)).toFixed(2);

                    $('#net_scm').html(net_scm);
                    $('#base_amt').html(base_amot);
                    $('#tax_amt').html(tax_amot);
                    $('#total_amt').html(total_amot);
                    $('#end_read_err').html("");
                    return true;
                }
            }
            else {
                $('#end_read_err').html('End reading feild is required.');
                $('#net_scm').html("");
                $('#base_amt').html("");
                $('#tax_amt').html("");
                $('#total_amt').html("");
                return false;
            }
        }
    </script>
@endpush