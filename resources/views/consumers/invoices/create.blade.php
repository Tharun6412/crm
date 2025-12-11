@extends('layouts.layout')
@section('title', "Invoice Generation")

@section('page-titla', 'Invoice Generation')

@section('page-content')
    <div id="add-invoice-success">
        <form action="{{ url('bill/invoice/') }}" name="add-invoice-form" id="add-invoice-form" method="post">
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
                                    <td>{{ $consumer->status->created_at }}</td>
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
                <div class="row">
                    <label for="" class="form_label">Invoice Type</label>
                    <select name="invoice_type" id="invoice_type" class="form_select">
                        <option value="">Select</option>
                        @foreach ($invoice_types as $type )
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
@endsection