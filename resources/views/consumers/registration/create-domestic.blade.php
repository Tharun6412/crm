
{{-- Domestic Registration --}}
@extends('layouts.layout')

@section('title', 'Registration')

@section('page-title', 'Registration')

@section('page-content')
<div class="container">
    <form id="add-domestic-form" action="{{ url('consumer/register') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-sm-4 col-md-4">
                <label>Geo Area</label>
                <div>
                    <select name="geo_area" id="geo_area" class="form-select" onchange="getDistrictsByGa(this.value)">
                        <option value="">All</option>
                        @foreach ($geo_areas as $ga)
                            <option value="{{ $ga->id }}">{{ $ga->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <label>District</label>
                <div>
                    <select name="district" id="district" class="form-select" onchange="getCasByDistrict(this.value)">
                        <option value="">All</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <label>Charge Area</label>
                <div>
                    <select name="charge_area" id="charge_area" class="form-select">
                        <option value="">All</option>
                        @foreach ($charge_areas as $ca)
                            <option value="{{ $ca->id }}">{{ $ca->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="mt-2 mb-2">Basic details&nbsp;:</div>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label">First Name&nbsp;:&nbsp;</label><span class="text-danger">*</span>
                    <div class="input-group">
                        <select name="title" id="title" class="form-select form-select-sm">
                            <option value="">Select</option>
                            @foreach ($titles->where('type', 1) as $title)
                                <option value="{{ $title->id }}">{{ $title->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="fname" id="fname" class="form-control form-control-sm" placeholder="First Name"/>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label">Last Name&nbsp;:&nbsp;</label><span class="text-danger">*</span></label>
                    <div>
                        <input type="text" name="lname" id="lname" class="form-control form-control-sm" placeholder="Last Name"/>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label">S/o / D/o / W/o&nbsp;:&nbsp;</label>
                    <div class="input-group">
                        <select name="cof" id="cof" class="form-select form-select-sm" style="width: 1%">
                            <option value="">Select</option>
                            @foreach ($titles->where('type', 2) as $title)
                                <option value="{{ $title->id }}">{{ $title->name }}</option>
                            @endforeach
                        </select>
                        <input name="cof_name" id="cof_name" class="form-control form-control-sm" placeholder="Relation Name" type="text"/>
                    </div>                        
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label">Email&nbsp;:&nbsp;</label>
                    <input type="text" name="email" id="email" class="form-control form-control-sm" placeholder="Email"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label">Aadhar number&nbsp;:&nbsp;</label><span class="text-danger">*</span>
                    <input maxlength="12" name="aadhar" id="aadhar" class="form-control form-control-sm" placeholder="Aadhar Number" type="number"/>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label">Registered mobile&nbsp;:&nbsp;</label><span class="text-danger">*</span>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">+91</span>
                        <input maxlength="10" type="number" name="phone" id="phone" class="form-control form-control-sm" placeholder="Mobile Number" aria-label="Mobile" aria-describedby="mobile"/>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label">Alternate contact number&nbsp;:&nbsp;</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">+91</span>
                        <input maxlength="10" type="number" name="phone_alt" id="phone_alt" class="form-control form-control-sm" placeholder="Alternate Contact Number" aria-label="Alt Mobile" aria-describedby="alt_mobile"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="cnsmr-details-title mt-2 mb-2">Security Deposit Scheme Details&nbsp;:</div>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12" id="schemes_list">
                <label>Security Deposit Schemes</label>
                <div>
                    <select name="scheme_id" id="scheme_id" class="form-select">
                        <option value="">All</option>
                        @foreach ($schemes as $scheme)
                            <option value="{{ $scheme->scheme->id }}">{{ $scheme->scheme->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="mt-2 mb-2">Nominee details&nbsp;:</div>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label" for="nominee">Nominee&nbsp;:&nbsp;</label>
                    <input name="nominee" id="nominee" class="form-control form-control-sm" placeholder="Nominee name" type="text"/>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label">Nominee Relation&nbsp;:&nbsp;</label>
                    <select name="nominee_relation_id" id="nominee_relation_id" class="form-select form-select-sm">
                        <option value="">Select</option>
                        @foreach ($nominee_relations as $relation)
                            <option value="{{ $relation->id }}">{{ $relation->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="cnsmr-details-title mt-2 mb-2">Address details&nbsp;:</div>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label" for="hno">Address line1&nbsp;:&nbsp;</label>
                    <input name="hno" id="hno" class="form-control form-control-sm" placeholder="Example H.No:1-11" type="text" />
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label" for="street">Address line2&nbsp;:&nbsp;</label>
                    <input name="street" id="street" class="form-control form-control-sm" placeholder="Example Street-No: 2A" type="text"/>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label" for="colony">Locality/Colony name&nbsp;:&nbsp;</label>
                    <input name="colony" id="colony" class="form-control form-control-sm" placeholder="Locality/Colony Name" type="text"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label" for="city">Town/Village/City&nbsp;:&nbsp;</label>
                    <input name="city" id="city" class="form-control form-control-sm" placeholder="Town / Village / City" type="text"/>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label" for="ward">Ward No&nbsp;:&nbsp;</label>
                    <input name="ward" id="ward" class="form-control form-control-sm" placeholder="Ward No" type="text"/>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label" for="pincode">Pincode&nbsp;:&nbsp;</label><span class="text-danger">*</span>
                    <input maxlength="6" name="pincode" id="pincode" class="form-control form-control-sm" placeholder="Pincode" type="number"/>
                </div>
            </div>
        </div>
        <div class="cnsmr-details-title mt-2 mb-2">Additional details&nbsp;:</div>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label" for="lpg_no">LPG Connections no (if any)&nbsp;:&nbsp;</label>
                    <input type="number" name="lpg_connections" id="lpg_connections" class="form-control form-control-sm" placeholder="LPG Connections"/>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label" for="dcq">DCQ&nbsp;:&nbsp;</label>
                    <input name="dcq" id="dcq" class="form-control form-control-sm" placeholder="DCQ" type="text"/>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label" for="expected_date">Expected Date&nbsp;:&nbsp;</label>
                    <div class="input-group input-group-sm">
                        <input name="expected_date" id="expected_date" class="form-control form-control-sm" placeholder="Expected Date( DD-MM-YYYY )" type="text"/>
                        <span class="input-group-text"><i class="mdi mdi-calendar-month-outline"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label" for="distance">Distance&nbsp;:&nbsp;</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="distance" id="distance" class="form-control form-control-sm" placeholder="Distance"/>
                        <span class="input-group-text" >km</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <!-- 1=> Own. 2=> Rented.-->
                    <label class="form-label" for="property_type">House&nbsp;:&nbsp;</label>
                    <select name="property_type" id="property_type" class="form-select form-select-sm">
                        <option value="">Select</option>
                        <option value="1">Own</option>
                        <option value="2">Rent</option>
                        <option value="3">Lease</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label" for="owner_name">Owner name (In case rented)&nbsp;:&nbsp;</label>
                    <input type="text" name="owner_name" id="owner_name" class="form-control form-control-sm" placeholder="Owner Name"/>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label" for="owner_phone">Owner contact number (In case rented)&nbsp;:&nbsp;</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">+91</span>
                        <input maxlength="10" type="number" name="owner_phone" id="owner_phone" class="form-control form-control-sm" placeholder="Owner Contact Number"/>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label" for="gas_required_id">Natural gas required for&nbsp;:&nbsp;</label>
                    <select name="gas_required_id" id="gas_required_id" class="form-select form-select-sm">
                        <option value="">Select</option>
                        @foreach ($gas_required_list as $list)
                            <option value="{{ $list->id }}">{{ $list->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label" for="tenant_name">Tenant name&nbsp;:&nbsp;</label>
                    <input type="text" name="tenant_name" id="tenant_name" class="form-control form-control-sm" placeholder="Tenant Name"/>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label" for="tenant_phone">Tenant contact number&nbsp;:&nbsp;</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">+91</span>
                        <input maxlength="10" type="number" name="tenant_phone" id="tenant_phone" class="form-control form-control-sm" placeholder="Tenant Contact Number"/>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="mb-3">
                    <label class="form-label" for="tenant_email">Tenant Email&nbsp;:&nbsp;</label>
                    <input type="text" name="tenant_email" id="tenant_email" class="form-control form-control-sm" placeholder="Tenant Email"/>
                </div>
            </div>
        </div>
        <div class="mt-2 mb-2">Documents&nbsp;:</div>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="mb-3">
                    <label class="form-label">Document Type&nbsp;:&nbsp;</label>
                    <select name="document_type[]" id="document_type_0" class="form-select form-select-sm">
                        <option value="">Select</option>
                        @foreach ($documents as $doc_val)
                            <option value="{{ $doc_val->id }}">{{ $doc_val->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="mb-3">
                    <label class="form-label">Documents&nbsp;&nbsp;:</label>
                    <div class="input-group input-group-sm">
                        <input type="file" name="document_list[]" id="document_list_0" class="form-control form-control-sm" id="inputGroupFile01">
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="mb-3">
                    <label class="form-label">Document Type&nbsp;:&nbsp;</label>
                    <select name="document_type[]" id="document_type_1" class="form-select form-select-sm">
                        <option value="">Select</option>
                        @foreach ($documents as $doc_val)
                            <option value="{{ $doc_val->id }}">{{ $doc_val->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="mb-3">
                    <label class="form-label">Documents&nbsp;&nbsp;:</label>
                    <div class="input-group input-group-sm">
                        <input type="file" name="document_list[]" id="document_list_1" class="form-control form-control-sm" id="inputGroupFile01">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="add-domestic-error"></div>
        </div>
        <div class="mb-3 mt-4">
            <div class="text-end">
                <button class="btn btn-success" type="submit"><i class="mdi mdi-check" aria-hidden="true">&nbsp;</i>Register domestic consumer</button>
                <a class="btn btn-warning" href="javascript:history.go(-1)"><i class="mdi mdi-chevron-left">&nbsp;</i>Back</a>
            </div>
        </div>
    </form>
</div>
@include('scripts.ajax-form-submit', ['form' => 'add-domestic'])
<script type="text/javascript">
    // Get Districts By GA
    function getDistrictsByGa(ga) 
    {
        $.get("{{ url('consumer/register/getDistrictsByGa') }}", { 'ga_id' : ga }, function(data) {
            $('#district').empty();
            let options = '<option value = "">All</option>'
            if(data.districts && data.districts.length > 0) {
                data.districts.forEach(function(dist) {
                    options += `<option value="${dist.id}">${dist.name}</option>`;
                });
            }
            $('#district').html(options);
        });
    }

    // Get Charge Areas By District
    function getCasByDistrict(district_id) {
        $.get("{{ url('consumer/register/getCasByDistrict') }}", { 'district_id' :district_id }, function(data) {
            $('#charge_area').empty();
            let options = '<option value="">All</option>'
            if(data.charge_areas && data.charge_areas.length > 0) {
                data.charge_areas.forEach(function(ca) {
                    options += `<option value="${ca.id}">${ca.name}</option>`;
                });
            }
            $('#charge_area').html(options);
        });
    }
</script>
@endsection
