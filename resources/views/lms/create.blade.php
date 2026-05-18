@extends('layouts.layout')

@section('title', 'Registration')

@section('page-title', 'Registration')

@section('page-content')

<div class="container-fluid border border-secondary-subtle bg-white rounded-3">
    <div id="add-lms-create-success">
        <form id="add-lms-create-form" action="{{ url('lms/leads/store') }}" method="POST">
            @csrf
            <div class="row bg-primary-subtle pb-3 rounded-1 p-2">
                <div class="col-sm-4 col-md-3">
                    <label for="ga_id" class="form-label">Geo Area</label>
                    <select name="ga_id" id="ga_id" class="form-select">
                        <option value="">GA Area</option>
                        @foreach ($geo_areas as $ga)
                            <option value="{{ $ga->id }}">{{ $ga->name }}</option>
                        @endforeach
                    </select>                </div>
                <div class="col-sm-4 col-md-3">
                    <label for="district_id" class="form-label">District</label>
                    <select name="district_id" id="district_id" class="form-select">
                        <option value="">District</option>
                    </select>
                </div>
                <div class="col-sm-4 col-md-3">
                    <label for="ca_id" class="form-label">Charge Area</label>
                    <select name="ca_id" id="ca_id" class="form-select" >
                        <option value="">CA Area</option>
                    </select>
                </div>
                <div class="col-sm-4 col-md-3">
                    <label for="area_id" class="form-label">Area</label>
                    <select name="area_id" id="area_id" class="form-select">
                        <option value="">Area</option>
                    </select>
                </div>
            </div>
            <div class="mt-3 mb-1 fs-5 fw-semibold text-primary">Basic Details&nbsp;:</div>
                <div class="row mb-2">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <label for="name" class="form-label">Name&nbsp;:</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <label id="mobile" class="form-label">Registered mobile&nbsp;:</label>
                        <div class="input-group">
                            <span class="input-group-text">+91</span>
                            <input maxlength="10" type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile Number" aria-label="Mobile" aria-describedby="mobile"/>
                        </div>
                    </div>
                </div>  
            <div>
            <div class="mt-3 mb-1 fs-5 fw-semibold text-primary">Address details&nbsp;:</div>
                <div class="row mb-2">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <label  class="form-label" for="address_line1">Address line1&nbsp;:&nbsp;</label>
                        <input  type="text" name="address_line1" id="address_line1" class="form-control" placeholder="" />
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <label class="form-label" for="address_line2" >Address line2&nbsp;:&nbsp;</label>
                        <input type="text" name="address_line2" id="address_line2" class="form-control" placeholder="" />
                    </div>
                </div>
            </div>
            <div class="mb-1 fs-5 fw-semibold text-primary">Additional details&nbsp;:</div>
                <div class="row mb-2 ">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label class="form-label" id="lead_channel_id">Medium&nbsp;:&nbsp;</label>
                        <select name="lead_channel_id" id="lead_channel_id" class="form-select">
                            <option>Select Medium</option>
                            @foreach ($channels as $channel )
                                <option value="{{$channel->id}}">{{ $channel->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label class="form-label" for="owner_ship">Ownership&nbsp;:&nbsp;</label>
                        <input type="text" name="owner_ship" id="owner_ship" class="form-control" placeholder="Ownership"/>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label class="form-label" for="lpg_service">LPG Service&nbsp;:&nbsp;</label>
                        <input type="text" name="lpg_service" id="lpg_service" class="form-control" placeholder="LPG Service" />
                    </div>
            </div>
            <div class="row mb-2">
                <label class="col-form-label ">Notes&nbsp;:<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <textarea name="notes" id="notes" class="form-control"></textarea>
                    <small class="text-muted">Maximum 225 Characters Allowed</small>
                </div>
            </div>
            <div id="add-lms-create-error" class="text-danger"></div>
            <div class="mb-3 mt-4">
                <div class="text-end">
                    <button class="btn btn-success" type="submit">
                        <i class="bi bi-check2-square" aria-hidden="true">&nbsp;</i>Register
                    </button>
                    <a class="btn btn-warning" href="{{ url('lms/leads') }}"><i class="bi bi-chevron-left">&nbsp;</i>Back</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@include('scripts.ajax-form-submit',['form' => 'add-lms-create'])
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
        // Choose Districts for charge areas
        $("#district_id").on('change', function(e) {
            $.get("{{ url('common/districtCas') }}", {'district_id': e.target.value}, function(response){
                let options = '<option value = "">Select Charge Area</option>';
                if(response.charge_areas && response.charge_areas.length > 0) {
                    response.charge_areas.forEach(function(dist) {
                        options += `<option value="${dist.id}">${dist.name}</option>`;
                    });
                }
                $('#ca_id').html(options);
            })
        });
        // Choose Charge Areas For Areas
        $("#ca_id").on('change', function(e) {
            $.get("{{ url('common/caAreas') }}", {'ca_id': e.target.value}, function(response){
                let options = '<option value = "">Select Area</option>';
                if(response.areas && response.areas.length > 0) {
                    response.areas.forEach(function(area) {
                        options += `<option value="${area.id}">${area.name}</option>`;
                    });
                }
                $('#area_id').html(options);
            })
        });
    });
</script>