
{{-- Domestic Registration --}}
@extends('layouts.layout')

@section('title', 'Registration')

@section('page-title', 'Registration')

@section('page-content')
    <div class="container-fluid border border-secondary-subtle bg-white rounded-3">
        <div id="add-domestic-success">
            <form id="add-domestic-form" action="{{ url('consumers/register/domestic') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row bg-primary-subtle pb-3 rounded-1 p-2">
                    <div class="col-sm-4 col-md-2">
                        <label>Geo Area&nbsp;:<span class="text-danger">*</span></label>
                        <div>
                            <select name="geo_area" id="geo_area" class="form-select" onchange="getDistrictsByGa(this.value)">
                                <option value="">Select GA</option>
                                @foreach ($geo_areas as $ga)
                                    <option value="{{ $ga->id }}">{{ $ga->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger validate-err-msg" id="geo_area-error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-2">
                        <label>District&nbsp;:<span class="text-danger">*</span></label>
                        <div>
                            <select name="district" id="district" class="form-select" onchange="getCasByDistrict(this.value)">
                                <option value="">Select District</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger validate-err-msg" id="district-error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-2">
                        <label>Charge Area&nbsp;:<span class="text-danger">*</span></label>
                        <div>
                            <select name="charge_area" id="charge_area" class="form-select" onchange="getCaAreas(this.value)">
                                <option value="">Select Charge Area</option>
                                @foreach ($charge_areas as $ca)
                                    <option value="{{ $ca->id }}">{{ $ca->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger validate-err-msg" id="charge_area-error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-2">
                        <label>Area&nbsp;:<span class="text-danger">*</span></label>
                        <div>
                            <select name="area" id="area" class="form-select" onchange="getAreaSubareas(this.value)">
                                <option value="">Select Area</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger validate-err-msg" id="area-error"></span>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-2">
                        <label>SubArea&nbsp;:</label>
                        <div>
                            <select name="subarea" id="subarea" class="form-select">
                                <option value="">Select SubArea</option>
                                @foreach ($subareas as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger validate-err-msg" id="subarea-error"></span>
                        </div>
                    </div>
                </div>
                <div class="mt-3 mb-1 fs-5 fw-semibold text-primary">Basic Details&nbsp;:</div>
                <div class="row mb-2">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <label class="form-label">Consumer Name&nbsp;:<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="title" id="title" class="form-select">
                                <option value="">Title</option>
                                @foreach ($titles->where('type', 1) as $title)
                                    <option value="{{ $title->id }}">{{ $title->name }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="fname" id="fname" class="form-control" placeholder="First Name"/>
                            <input type="text" name="lname" id="lname" class="form-control" placeholder="Last Name"/>
                        </div>
                        <span class="text-danger validate-err-msg" id="title-error"></span>
                        <span class="text-danger validate-err-msg" id="fname-error"></span>
                        <span class="text-danger validate-err-msg" id="lname-error"></span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <label class="form-label">S/o / D/o / W/o&nbsp;:&nbsp;</label>
                        <div class="input-group">
                            <select name="cof" id="cof" class="form-select" style="width: 1%">
                                <option value="">Select</option>
                                @foreach ($titles->where('type', 2) as $title)
                                    <option value="{{ $title->id }}">{{ $title->name }}</option>
                                @endforeach
                            </select>
                            <input name="cof_name" id="cof_name" class="form-control" placeholder="Name" type="text"/>
                        </div>
                    </div>
                </div>
                <div class="row mb-2 pt-2">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label class="form-label">Email&nbsp;:&nbsp;</label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="Email"/>
                        <span class="text-danger validate-err-msg" id="email-error"></span>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label class="form-label">Aadhar number&nbsp;:<span class="text-danger">*</span></label>
                        <input maxlength="12" name="aadhar" id="aadhar" class="form-control" placeholder="Aadhar Number" type="text"/>
                        <span class="text-danger validate-err-msg" id="aadhar-error"></span>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label class="form-label">Registered mobile&nbsp;:<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">+91</span>
                            <input maxlength="10" type="text" name="phone" id="phone" class="form-control" placeholder="Mobile Number" aria-label="Mobile" aria-describedby="mobile"/>
                        </div>
                        <span class="text-danger validate-err-msg" id="phone-error"></span>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label class="form-label">Alternate number&nbsp;:&nbsp;</label>
                        <div class="input-group">
                            <span class="input-group-text">+91</span>
                            <input maxlength="10" type="text" name="phone_alt" id="phone_alt" class="form-control" placeholder="Alternate Contact Number" aria-label="Alt Mobile" aria-describedby="alt_mobile"/>
                        </div>
                        <span class="text-danger validate-err-msg" id="phone_alt-error"></span>
                    </div>
                </div>
                <div class="pt-2 pb-2"><hr></div>
                <div class="mb-1 fs-5 fw-semibold text-primary">Security Deposit Scheme Details&nbsp;:</div>
                <div class="row">
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <label class="form-label">Connection Type&nbsp;:<span class="text-danger">*</span></label>
                        <div>
                            <select name="connection_type" id="connection_type" class="form-select" onchange="getSchemesByType(this.value)">
                                <option value="">Select Type</option>
                                @foreach ($connection_types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="text-danger validate-err-msg" id="connection_type-error"></span>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <label class="form-label">Security Deposit Schemes&nbsp;:<span class="text-danger">*</span></label>
                        <div>
                            <select name="scheme_id" id="scheme_id" class="form-select" onchange="getSchemeDetails(this.value)">
                                <option value="">Select scheme</option>
                                @foreach ($schemes as $scheme)
                                    <option value="{{ $scheme->scheme->id }}">{{ $scheme->scheme->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="text-danger validate-err-msg" id="scheme_id-error"></span>
                    </div>
                </div>    
                <div class="row">    
                    <div class="col-md-8 col-sm-6 col-xs-12 d-none pt-3" id="scheme_data">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr class="table-primary">
                                    <td colspan="5"><span class="fw-semibold" id="scheme_name_details"></span></td>
                                </tr>
                                <tr class="table-secondary text-end">
                                    <td>Meter Deposit</td>
                                    <td>Consumption Deposit</td>
                                    <td>Registration</td>
                                    <td>EMI</td>
                                    <td>Rental</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-end"><span id="security"></span></td>
                                    <td class="text-end"><span id="consumption"></span></td>
                                    <td class="text-end"><span id="registration"></span></td>
                                    <td class="text-end"><span id="emi_amount"></span></td>
                                    <td class="text-end"><span id="rental_amount"></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-3 mb-1 fs-5 fw-semibold text-primary">Nominee details&nbsp;:</div>
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label class="form-label" for="nominee">Nominee&nbsp;:&nbsp;</label>
                        <input name="nominee" id="nominee" class="form-control" placeholder="Nominee name" type="text"/>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label class="form-label">Nominee Relation&nbsp;:&nbsp;</label>
                        <select name="nominee_relation_id" id="nominee_relation_id" class="form-select">
                            <option value="">Select</option>
                            @foreach ($nominee_relations as $relation)
                                <option value="{{ $relation->id }}">{{ $relation->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-3 mb-1 fs-5 fw-semibold text-primary">Address details&nbsp;:</div>
                <div class="row mb-2">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label class="form-label" for="hno">Address line1&nbsp;:&nbsp;</label>
                        <input name="hno" id="hno" class="form-control" placeholder="Example H.No:1-11" type="text" />
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label class="form-label" for="street">Address line2&nbsp;:&nbsp;</label>
                        <input name="street" id="street" class="form-control" placeholder="Example Street-No: 2A" type="text"/>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label class="form-label" for="colony">Locality/Colony name&nbsp;:&nbsp;</label>
                        <input name="colony" id="colony" class="form-control" placeholder="Locality/Colony Name" type="text"/>
                    </div>
                </div>
                <div class="row mb-2 pt-2">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label class="form-label" for="city">Town/Village/City&nbsp;:&nbsp;</label>
                        <input name="city" id="city" class="form-control" placeholder="Town / Village / City" type="text"/>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label class="form-label" for="ward">Ward No&nbsp;:&nbsp;</label>
                        <input name="ward" id="ward" class="form-control" placeholder="Ward No" type="text"/>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label class="form-label" for="pincode">Pincode&nbsp;:<span class="text-danger">*</span></label>
                        <input maxlength="6" name="pincode" id="pincode" class="form-control" placeholder="Pincode" type="text"/>
                        <span class="text-danger validate-err-msg" id="pincode-error"></span>
                    </div>
                </div>                
                <div class="pt-2 pb-2"><hr></div>
                <div class="mb-1 fs-5 fw-semibold text-primary">Additional details&nbsp;:</div>
                <div class="row mb-2 pt-2">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <label class="form-label" for="lpg_id">LPG Id&nbsp;:<span class="text-danger">*</span></label>
                        <input name="lpg_id" id="lpg_id" class="form-control" placeholder="lpg id" type="text"/>
                        <span class="text-danger validate-err-msg" id="lpg_id-error"></span>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <label class="form-label" for="lpg_no">LPG Connections no (if any)&nbsp;:&nbsp;</label>
                        <input type="number" name="lpg_connections" id="lpg_connections" class="form-control" placeholder="LPG Connections"/>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <label class="form-label" for="dcq">Expected required PNG per day&nbsp;:&nbsp;</label>
                        <input name="dcq" id="dcq" class="form-control" placeholder="DCQ" type="text"/>
                    </div>
                </div>
                <div class="row mb-2 pt-2">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <label class="form-label" for="expected_date">Expected Date&nbsp;:&nbsp;</label>
                        <div class="input-group">
                            <input name="expected_date" id="expected_date" class="form-control" placeholder="Expected Date( DD-MM-YYYY )" type="text"/>
                            <span class="input-group-text"><i class="bi bi-calendar2-event"></i></span>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <label class="form-label" for="distance">Distance&nbsp;:&nbsp;</label>
                        <div class="input-group">
                            <input type="text" name="distance" id="distance" class="form-control" placeholder="Distance"/>
                            <span class="input-group-text" >Mts</label>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <!-- 1=> Own. 2=> Rented.-->
                        <label class="form-label" for="property_type">House&nbsp;:&nbsp;</label>
                        <select name="property_type" id="property_type" class="form-select">
                            <option value="">Select</option>
                            <option value="1">Own</option>
                            <option value="2">Rent</option>
                            <option value="3">Lease</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2 pt-2">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <label class="form-label" for="owner_name">Owner name (In case rented)&nbsp;:&nbsp;</label>
                        <input type="text" name="owner_name" id="owner_name" class="form-control" placeholder="Owner Name"/>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <label class="form-label" for="owner_phone">Owner contact number (In case rented)&nbsp;:&nbsp;</label>
                        <div class="input-group">
                            <span class="input-group-text">+91</span>
                            <input maxlength="10" type="text" name="owner_phone" id="owner_phone" class="form-control" placeholder="Owner Contact Number"/>
                            <span class="text-danger validate-err-msg" id="owner_phone-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <label class="form-label" for="gas_required_id">Natural gas required for&nbsp;:&nbsp;</label>
                        <select name="gas_required_id" id="gas_required_id" class="form-select">
                            <option value="">Select</option>
                            @foreach ($gas_required_list as $list)
                                <option value="{{ $list->id }}">{{ $list->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-2 pt-2">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <label class="form-label" for="tenant_name">Tenant name&nbsp;:&nbsp;</label>
                        <input type="text" name="tenant_name" id="tenant_name" class="form-control" placeholder="Tenant Name"/>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <label class="form-label" for="tenant_phone">Tenant contact number&nbsp;:&nbsp;</label>
                        <div class="input-group">
                            <span class="input-group-text">+91</span>
                            <input maxlength="10" type="text" name="tenant_phone" id="tenant_phone" class="form-control" placeholder="Tenant Contact Number"/>
                            <span class="text-danger validate-err-msg" id="tenant_phone-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <label class="form-label" for="tenant_email">Tenant Email&nbsp;:&nbsp;</label>
                        <input type="text" name="tenant_email" id="tenant_email" class="form-control" placeholder="Tenant Email"/>
                    </div>
                </div>
                <div class="mt-3 mb-1 fs-5 fw-semibold text-primary">Referral Code&nbsp;:</div>
                <div class="row mb-2 pt-2">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <label class="form-label" for="referral_code">Referral Code&nbsp;:&nbsp;</label>
                        <input type="text" name="referral_code" id="referral_code" class="form-control" placeholder="Enter the Referral Code"/>
                        <span class="text-danger validate-err-msg" id="referral_code-error"></span>
                    </div>
                </div>
                <div class="mt-3 mb-1 fs-5 fw-semibold text-primary">Documents&nbsp;:</div>
                <div class="row mb-2 pt-2">
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <label class="form-label">Document Type&nbsp;:<span class="text-danger">*</span></label>
                        <select name="document_type[]" id="document_type_0" class="form-select">
                            <option value="">Select</option>
                            @foreach ($documents as $doc_val)
                                <option value="{{ $doc_val->id }}"@selected($doc_val->id == \App\Enums\DocumentType::AADHAR->value)>{{ $doc_val->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger validate-err-msg" id="document_type_0-error"></span>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-6">
                        <label class="form-label">Documents&nbsp;:<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="file" name="dc_file_list[]" id="dc_file_list_0" class="form-control">
                            <span class="text-danger validate-err-msg" id="dc_file_list_0-error"></span>
                        </div>
                    </div>
                </div>
                <div class="row mb-2 pt-2">
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <label class="form-label">Document Type&nbsp;:<span class="text-danger">*</span></label>
                        <select name="document_type[]" id="document_type_1" class="form-select">
                            <option value="">Select</option>
                            @foreach ($documents as $doc_val)
                                <option value="{{ $doc_val->id }}">{{ $doc_val->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger validate-err-msg" id="document_type_1-error"></span>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-6">
                        <label class="form-label">Documents&nbsp;:<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="file" name="dc_file_list[]" id="dc_file_list_1" class="form-control">
                            <span class="text-danger validate-err-msg" id="dc_file_list_1-error"></span>
                        </div>
                    </div>
                </div>
                <div class="row mb-2 pt-2">
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <label class="form-label">Document Type&nbsp;:</label>
                        <select name="document_type[]" id="document_type_2" class="form-select">
                            <option value="">Select</option>
                            @foreach ($documents as $doc_val)
                                <option value="{{ $doc_val->id }}">{{ $doc_val->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger validate-err-msg" id="document_type_2-error"></span>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-6">
                        <label class="form-label">Documents&nbsp;:</label>
                        <div class="input-group">
                            <input type="file" name="dc_file_list[]" id="dc_file_list_2" class="form-control">
                            <span class="text-danger validate-err-msg" id="dc_file_list_2-error"></span>
                        </div>
                    </div>
                </div>
                <div class="row mb-2 pt-2">
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <label class="form-label">Document Type&nbsp;:</label>
                        <select name="document_type[]" id="document_type_3" class="form-select">
                            <option value="">Select</option>
                            @foreach ($documents as $doc_val)
                                <option value="{{ $doc_val->id }}">{{ $doc_val->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger validate-err-msg" id="document_type_3-error"></span>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-6">
                        <label class="form-label">Documents&nbsp;:</label>
                        <div class="input-group">
                            <input type="file" name="dc_file_list[]" id="dc_file_list_3" class="form-control">
                            <span class="text-danger validate-err-msg" id="dc_file_list_3-error"></span>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <i class="bi bi-check-square"></i>&nbsp;I shared all details with consumer as per MeghaGas policy.<br/>
                    <i class="bi bi-check-square"></i>&nbsp;Consumer agreed with MeghaGas Policies.
                </div>
                <div class="row">
                    <div id="add-domestic-error"></div>
                </div>
                <div class="mb-3 mt-4">
                    <div class="text-end">
                        <button class="btn btn-success" type="submit">
                            <i class="bi bi-check2-square" aria-hidden="true">&nbsp;</i>Register Domestic Consumer
                        </button>
                        <a class="btn btn-warning" href="{{ url('consumers') }}"><i class="bi bi-chevron-left">&nbsp;</i>Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-file-submit', ['form' => 'add-domestic'])
    @include('scripts.datepicker', ['list' => ['expected_date']])
    <script type="text/javascript">
        // Get Districts By GA
        function getDistrictsByGa(ga) 
        {
            $.get("{{ url('common/gaDistricts') }}", { 'ga_id' : ga }, function(data) {
                $('#district').empty();
                let options = '<option value = "">Select district</option>'
                if(data.districts && data.districts.length > 0) {
                    data.districts.forEach(function(dist) {
                        options += `<option value="${dist.id}">${dist.name}</option>`;
                    });
                }
                $('#district').html(options);
            });
        }

        // Get Schemes By Connection Type
        function getSchemesByType(type_id)
        {
            var ga = $('#geo_area').val();
            var domestic = "{{ \App\Enums\SegmentType::DOMESTIC->value }}";
            $('#scheme_data').addClass('d-none');
            $.get("{{ url('common/gaSchemesByType') }}", { 'type_id' : type_id , 'ga_id' : ga, 'segment_id' :domestic}, function(data) {
                $('#scheme_id').empty();
                let options1 = '<option value="">Select scheme</option>'
                if(data.schemes && data.schemes.length > 0) {
                    data.schemes.forEach(function(scheme) {
                        options1 += `<option value="${scheme.scheme.id}">${scheme.scheme.name}</option>`;
                    });
                }
                $('#scheme_id').html(options1);
            });
        }
        // Get Scheme Details
        function getSchemeDetails(scheme_id)
        {
            $.get("{{ url('common/schemeDetails') }}", {'scheme_id' : scheme_id}, function(data) {
                if(data.scheme_details != null) {
                    $('#scheme_name_details').html(data.scheme_details.name);
                    $('#security').html(data.scheme_details.security);
                    $('#consumption').html(data.scheme_details.consumption);
                    $('#registration').html(data.scheme_details.registration);
                    $('#emi_amount').html(data.scheme_details.emi_amount);
                    $('#rental_amount').html(data.scheme_details.rental_amount);
                    $('#scheme_data').removeClass('d-none');
                }
                else {
                    $('#scheme_data').addClass('d-none');
                }
            });
        }

        // Get Charge Areas By District
        function getCasByDistrict(district_id) {
            $.get("{{ url('common/districtCas') }}", { 'district_id' :district_id }, function(data) {
                $('#charge_area').empty();
                let options = '<option value="">Select charge area</option>'
                if(data.charge_areas && data.charge_areas.length > 0) {
                    data.charge_areas.forEach(function(ca) {
                        options += `<option value="${ca.id}">${ca.name}</option>`;
                    });
                }
                $('#charge_area').html(options);
            });
        }

        // Get Areas By Charge Area
        function getCaAreas(ca_id) {
            $.get("{{ url('common/caAreas') }}", { 'ca_id' :ca_id }, function(data) {
                $('#area').empty();
                let options = '<option value="">Select area</option>'
                if(data.areas && data.areas.length > 0) {
                    data.areas.forEach(function(value) {
                        options += `<option value="${value.id}">${value.name}</option>`;
                    });
                }
                $('#area').html(options);
            });
        }
        // Get Subarea by Area
        function getAreaSubareas(area_id){
            $.get("{{ url('common/areaSubareas') }}", {'area_id' :area_id}, function(data) {
                $('#subarea').empty();
                let options = '<option value="">Select SubArea</option>'
                if(data.subareas && data.subareas.length > 0) {
                    data.subareas.forEach(function(value) {
                        options += `<option value="${value.id}">${value.name}</option>`;
                    });
                }
                $('#subarea').html(options);
            });
        }
    </script>
@endpush

