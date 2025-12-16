@extends('layouts.layout')

@section('title', "Gas Bill")
@section('page-title', 'Gas Invoice Generation')

@section('page-content')
    <div id="add-gas-bill-success">
        <form action="{{ url('bill/gasInvoice/') }}" name="add-gas-bill-form" id="add-gas-bill-form" method="post">
            @csrf
            <input type="hidden" id="id" name="id" value="{{ $consumer->id }}">
            <div class="card mb-2">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td>CRN</td>
                                    <td>:</td>
                                    <td>{{ $consumer->crn }}</td>
                                </tr>
                                <tr>
                                    <td>Name</td>
                                    <td>:</td>
                                    <td>{{ $consumer->titleDisplay->name }}&nbsp;{{ $consumer->name }}</td>
                                </tr>
                                <tr>
                                    <td>Mobile</td>
                                    <td>:</td>
                                    <td>{{ $consumer->phone }}</td>
                                </tr>
                                <tr>
                                    <td>Consumer Type</td>
                                    <td>:</td>
                                    <td>{{ $consumer->segment->name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td>Status</td>
                                    <td>:</td>
                                    <td>{{ $consumer->status->name }}</td>
                                </tr>
                                <tr>
                                    <td>Status Date</td>
                                    <td>:</td>
                                    <td>{{ $consumer->statusHistory->first()->created_at->format('d-m-Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Meter No</td>
                                    <td>:</td>
                                    <td>{{ $consumer->meter->meter_no }}</td>
                                </tr>
                                <tr>
                                    <td>Meter Initial Reading</td>
                                    <td>:</td>
                                    <td>{{ $consumer->meter->initial_reading }}</td>
                                    <input type="hidden" id="meter_init_reading" name="meter_init_reading" value="{{ $consumer->meter->initial_reading }}">
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card mb-2">
                @php
                    $start_date = (!empty($invoice)) ? $invoice->consumption->last()->date_to->format('Y-m-d') : ($consumer->statusHistory->first()->created_at->format('Y-m-d'));
                    $invEndReading = (!empty($invoice) and $invoice->consumption->count() > 0)
                        ? $invoice->consumption->last()->curr_reading
                        : 0;
                    $startReading = ($invEndReading > 0) ? $invEndReading : (($consumer->meter->initial_reading >= 0) ? $consumer->meter->initial_reading : "");
                @endphp
                @if (!empty($invoice))
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td>Invoice No.</td>
                                        <td>:</td>
                                        <td>{{ $invoice->invoice_number }}</td>
                                    </tr>
                                    <tr>
                                        <td>Invoice Date</td>
                                        <td>:</td>
                                        <td>{{ $invoice->invoice_date->format('d-m-Y') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td>Invoice End Reading</td>
                                        <td>:</td>
                                        <td>{{ $invEndReading }}</td>
                                        <input type="hidden" id="inv_end_reading" name="inv_end_reading" value={{ $invEndReading }} >
                                    </tr>
                                    <tr>
                                        <td>Invoice Amount</td>
                                        <td>:</td>
                                        <td>{{ $invoice->total_amount }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                <span class="alert alert-info">No Previous Invoices Found..</span>
                @endif
            </div>
            @if (!empty($consumer->meter->meter_no) && $startReading >= 0)
                @if ($start_date == date('Y-m-d'))
                    <div class="alert alert-danger">Invoice already generated or consumer activated today.</div>
                @elseif ($bill_days < 10)
                    <div class="alert alert-danger">Billing Frequency should be greater than equal to 10 days.</div>
                @elseif ($prices->isEmpty() OR $prices->last()->basic_price <= 0 OR $prices->last()->tax_value <= 0)
                    <div class="alert alert-danger">No price record found. Please update the price.</div>
                @else
                    <div class="card mb-2">
                        <div class="row">
                            <label for="start_reading" class="col-sm-4 col-form-label">Start Reading&nbsp;:&nbsp;</label>
                            <div class="col-sm-8">
                                <span>{{ $startReading }}</span>
                                <input type="hidden" id="start_reading" name="start_reading" value="{{ $startReading }}">
                            </div>
                        </div>
                        <div class="row">
                            <label for="end_reading" class="col-sm-4 col-form-label">Meter End Reading&nbsp;:&nbsp;<i class="text-danger">*</i></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="end_reading" id="end_reading" placeholder="Enter meter end reading." onchange="calculateReadings(this.value)">
                                <span class="text-danger" id="end_read_err"></span>
                            </div>
                        </div>
                        <div class="row">
                            <label for="start_date" class="col-sm-4 col-form-label">Start Date&nbsp;:&nbsp;</label>
                            <div class="col-sm-8">
                                <span>{{ date('d-m-Y', strtotime($start_date)) }}</span>
                                <input type="hidden" id="start_date" name="start_date" value="{{ $start_date  }}">
                            </div>
                        </div>
                        <div class="row">
                            <label for="end_date" class="col-sm-4 col-form-label">End Date&nbsp;:&nbsp;</label>
                            <div class="col-sm-8">
                                <span>{{ date('d-m-Y') }}</span>
                                <input type="hidden" id="end_date" name="end_date" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="row">
                            <label for="unit_price" class="col-sm-4 col-form-label">Unit Price&nbsp;:&nbsp;</label>
                            <div class="col-sm-8">
                                <span>{{ $prices->last()->basic_price }}</span>
                                <input type="hidden" id="unit_price" name="unit_price" value="{{ $prices->last()->basic_price }}">
                            </div>
                        </div>
                        <input type="hidden" name="cust_err_msg" id="cust_err_msg">
                        <div class="row">
                            <label for="vat" class="col-sm-4 col-form-label">Vat&nbsp;:&nbsp;</label>
                            <div class="col-sm-8">
                                <span>{{ $prices->last()->tax_value }}</span>
                                <input type="hidden" id="tax_price" name="tax_price" value="{{ $prices->last()->tax_value }}">
                            </div>
                        </div>
                        <div class="row">
                            <label for="base_amount" class="col-sm-4 col-form-label">Net SCM&nbsp;:&nbsp;</label>
                            <div class="col-sm-8">
                                <span id="net_scm"></span>
                            </div>
                        </div>
                        <div class="row">
                            <label for="base_amount" class="col-sm-4 col-form-label">Base Amount&nbsp;:&nbsp;</label>
                            <div class="col-sm-8">
                                <span id="base_amt"></span>
                            </div>
                        </div>
                        <div class="row">
                            <label for="tax_amount" class="col-sm-4 col-form-label">Tax Amount&nbsp;:&nbsp;</label>
                            <div class="col-sm-8">
                                <span id="tax_amt"></span>
                            </div>
                        </div>
                        <div class="row">
                            <label for="total_amount" class="col-sm-4 col-form-label">Total Amount&nbsp;:&nbsp;</label>
                            <div class="col-sm-8">
                                <span id="total_amt"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div id="add-gas-bill-error"></div>
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-sm btn-success" type="submit"><i class="bi bi-plus-square">&nbsp;</i>Generate Bill</button>
                    </div>
                @endif
            @else
                <div class="alert alert-danger">Please update the Meter no. / Initial meter reading.</div>
            @endif
        </form>
    </div>
@include('scripts.ajax-form-submit', ['form' => 'add-gas-bill'])
    <script type="text/javascript">
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
@endsection()