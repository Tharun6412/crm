{{-- Gas invoice generations --}}
@extends('layouts.layout')

@section('title', "Gas Bill")

@section('page-title', 'Gas Invoice Generation')

@section('page-content')
<div id="add-gas-bill-success" class="mt-2">
    <form action="{{ url('bill/gasInvoice/') }}" name="add-gas-bill-form" id="add-gas-bill-form" method="post">
        @csrf
        <input type="hidden" id="id" name="id" value="{{ $consumer->id }}">
        <input type="hidden" id="meter_init_reading" name="meter_init_reading" value="{{ $consumer->activeMeter->initial_reading }}">
        <div class="mb-2 border">
            @php
                    $start_date = (!empty($invoice)) ? $invoice->consumption->date_to->format('Y-m-d') : ($consumer->statusHistory->first()->created_at->format('Y-m-d'));
                    $invEndReading = (!empty($invoice) and $invoice->consumption()->exists())
                    ? $invoice->consumption->curr_reading
                    : 0;
                    $meterChange = $consumer->meterChanges()->where('status_id', \App\Enums\MeterChange::PENDING->value)->first();
                    if ($consumer->meterChanges()->where('status_id', \App\Enums\MeterChange::PENDING->value)->exists()) {
                        $startReading = $consumer->activeMeter->initial_reading;
                        $old_consumption = $meterChange?->consumption;
                    }
                    else {
                        $startReading = ($invEndReading > 0) ? $invEndReading : (($consumer->activeMeter->initial_reading >= 0) ? $consumer->activeMeter->initial_reading : "");
                        $old_consumption = 0;
                    }
            @endphp
            @if (!empty($invoice))
                <div class="p-2">
                    <x-consumer.invoice-details :invoice="$invoice" type="3" class="bg-info-subtle mt-2" />
                    <input type="hidden" id="inv_end_reading" name="inv_end_reading" value={{ $invEndReading }} >
                </div>
                <div class="bg-secondary-subtle rounded m-2 p-2">
                    <div class="row">
                        <div class="col-sm-2 text-end fw-semibold">Scheme Name : </div>
                        <div class="col-sm-4">{{ $consumer->scheme->scheme->name }}</div>
                        <div class="col-sm-2 text-end fw-semibold">Scheme Amount : </div>
                        <div class="col-sm-4">{{ $consumer->scheme->scheme->total_deposit}}</div>
                        <div class="col-sm-2 text-end fw-semibold">Paid Amount : </div>
                        <div class="col-sm-4">{{ $consumer->scheme->paid_deposit ?? 0 }}</div>
                        <div class="col-sm-2 text-end fw-semibold">Balance : </div>
                        <div class="col-sm-4">{{ $consumer->scheme->balance ?? 0 }}</div>
                        @if ($consumer->scheme?->emi_amount > 0)
                            @php
                                $no_of_emis = $consumer->scheme->paid_deposit/$consumer->scheme->emi_amount;
                            @endphp
                            <div class="col-sm-2 text-end fw-semibold">Emi Amount :</div>
                            <div class="col-sm-4">{{ $consumer->scheme?->emi_amount }}</div>
                            <div class="col-sm-2 text-end fw-semibold">No of EMIs paid : </div>
                            <div class="col-sm-4">{{ round($no_of_emis) ?? 0 }}</div>
                        @endif
                    </div>
                </div>
            @else
                <div>
                    <x-consumer.basic-details :consumer="$consumer" type="3" class="bg-info-subtle"/>
                </div>
                <div class="alert alert-warning m-2">No Previous Invoices Found..!</div>
                <input type="hidden" id="inv_end_reading" name="inv_end_reading" value={{ $invEndReading }} >
                <div class="bg-secondary-subtle rounded m-2">
                    <div class="row p-2 mb-2">
                        <div class="col-sm-2 text-end fw-semibold">Scheme Name : </div>
                        <div class="col-sm-4">{{ $consumer->scheme->scheme->name }}</div>
                        <div class="col-sm-2 text-end fw-semibold">Scheme Amount : </div>
                        <div class="col-sm-4">{{ $consumer->scheme->scheme->total_deposit}}</div>
                        <div class="col-sm-2 text-end fw-semibold">Paid Amount : </div>
                        <div class="col-sm-4">{{ $consumer->sdPayment->sum('amount') ?? 0 }}</div>
                        <div class="col-sm-2 text-end fw-semibold">Balance : </div>
                        <div class="col-sm-4">{{ $consumer->sdPayment->last()->balance ?? $consumer->scheme->scheme->total_deposit }}   </div>
                        @if ($consumer->scheme->scheme->emi_amount > 0)
                            <div class="col-sm-2 text-end fw-semibold">Emi Amount :</div>
                            <div class="col-sm-4">{{ $consumer->scheme->scheme->emi_amount }}</div>
                            <div class="col-sm-2 text-end fw-semibold">No of EMIs paid : </div>
                            <div class="col-sm-4">{{ $consumer->sdPayment->last()->emi_no ?? 0 }}</div>
                        @endif
                    </div>
                </div>
            @endif
        @if (!empty($consumer->activeMeter->meter_no) AND $startReading >= 0)
            @if ($start_date == date('Y-m-d'))
                <div class="alert alert-danger  m-2 text-center">Invoice already generated or consumer activated today.</div>
            @elseif ($bill_days < 1)
                <div class="alert alert-danger  m-2 text-center">Billing Frequency should be greater than equal to 10 days.</div>
            @elseif ($prices->isEmpty() OR $prices->last()->basic_price <= 0 OR $prices->last()->tax_value <= 0)
                <div class="alert alert-danger  m-2 text-center">No price record found. Please update the price.</div>
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
                                <label for="old_consumption" class="col-sm-4 col-form-label text-end">Old Consumption&nbsp;:&nbsp;</label>
                                <div class="col-sm-8">
                                    <label class="col-form-label">{{ $old_consumption }}</label>
                                    <input type="hidden" id="old_consumption" name="old_consumption" value="{{ $old_consumption }}">
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
                                <label for="total_amount" class="col-sm-4 col-form-label text-end">Total Amount&nbsp;(A)&nbsp;:&nbsp;</label>
                                <div class="col-sm-8">
                                    <label class="col-form-label" id="total_amt"></label>
                                </div>
                                <label for="adv_amount" class="col-sm-4 col-form-label text-end">Advance Amount&nbsp;(B)&nbsp;:&nbsp;</label>
                                <div class="col-sm-8">
                                    <label class="col-form-label" id="adv_amt">{{ numberFormat($advance,2) }}</label>
                                    <input type="hidden" id="adv_amount" value="{{ $advance }}">
                                </div>
                                <label for="payable_amount" class="col-sm-4 col-form-label text-end">Payable Amount&nbsp;(A-B)&nbsp;:&nbsp;</label>
                                <div class="col-sm-8">
                                    <label class="col-form-label" id="payable_amt"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="cust_err_msg" id="cust_err_msg">
                <div id="add-gas-bill-error"></div>
            @endif
        @else
            <div class="alert alert-danger  m-2 text-center">Please update the Meter number / Initial meter reading.</div>
        @endif
    </div>
    </form>
</div>
@endsection()
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-form-submit', ['form' => 'add-gas-bill'])
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
                    $('#payable_amt').html("");
                    return false;
                }
                else {
                    var old_consumption = $('#old_consumption').val();
                    var adv_amt = $('#adv_amount').val();
                    var net_scm = Number(end_read - start_read).toFixed(3);
                    var total_scm = (parseFloat(net_scm) + parseFloat(old_consumption)).toFixed(3);
                    var price = $('#unit_price').val();
                    var vat = $('#tax_price').val();
                    var base_amot = (total_scm * price).toFixed(2);
                    var tax_amot = (parseFloat(base_amot * vat)/100).toFixed(2);
                    var total_amot = Number(parseFloat(base_amot) + parseFloat(tax_amot)).toFixed(2);
                    var payable_amot = Number(parseFloat(total_amot) - parseFloat(adv_amt)).toFixed(2);
                    if(payable_amot <= 0) { payable_amot = 0}

                    $('#net_scm').html(total_scm);
                    $('#base_amt').html(base_amot);
                    $('#tax_amt').html(tax_amot);
                    $('#total_amt').html(total_amot);
                    $('#payable_amt').html(payable_amot);
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
                $('#payable_amt').html("");
                return false;
            }
        }
    </script>
@endpush