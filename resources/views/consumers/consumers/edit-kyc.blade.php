{{-- Edit Customer Form --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Customer - {{ $consumer->crn ?? $consumer->t_crn }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="edit-success">
                <form id="edit-form" action="{{ url('consumers/kyc/'.$consumer->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <label class="form-label col-sm-4 text-end">Consumer Name&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <select name="title" id="title" class="form-select form-select-sm">
                                    <option value="">Title</option>
                                    @foreach ($titles->where('type', 1) as $title)
                                        <option value="{{ $title->id }}">{{ $title->name }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="fname" id="fname" class="form-control form-control-sm" placeholder="First Name"/>
                                <input type="text" name="lname" id="lname" class="form-control form-control-sm" placeholder="Last Name"/>
                            </div>
                            <span class="text-danger validate-err-msg" id="title-error"></span>
                            <span class="text-danger validate-err-msg" id="fname-error"></span>
                            <span class="text-danger validate-err-msg" id="lname-error"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="form-label col-sm-4 text-end">S/o / D/o / W/o&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <select name="cof" id="cof" class="form-select form-select-sm" style="width: 1%">
                                    <option value="">Select</option>
                                    @foreach ($titles->where('type', 2) as $title)
                                        <option value="{{ $title->id }}">{{ $title->name }}</option>
                                    @endforeach
                                </select>
                                <input name="cof_name" id="cof_name" class="form-control form-control-sm" placeholder="Name" type="text"/>
                            </div>
                            <span class="text-danger validate-err-msg" id="cof-error"></span>
                            <span class="text-danger validate-err-msg" id="cof_name-error"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-4 text-end">Email&nbsp;:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control form-control-sm" name="email" id="email" placeholder="Enter Email">
                            <span class="text-danger validate-err-msg" id="email-error"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-4 text-end">Phone<span class="text-danger">&nbsp;*</span>&nbsp;:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control form-control-sm" name="phone" id="phone" placeholder="Enter Phone">
                            <span class="text-danger validate-err-msg" id="phone-error"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-4 text-end">Alternate Phone&nbsp;:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control form-control-sm" name="phone_alt" id="phone_alt" placeholder="Enter Alternate Mobile">
                            <span class="text-danger validate-err-msg" id="phone_alt-error"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-4 text-end">Aadhar<span class="text-danger">&nbsp;*</span>&nbsp;:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control form-control-sm" name="aadhar" id="aadhar" placeholder="Enter Aadhar">
                            <span class="text-danger validate-err-msg" id="aadhar-error"></span>
                        </div>
                    </div>
                    <div class="mt-3 mb-1 fs-5 fw-semibold text-primary">Nominee details&nbsp;:</div>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="form-label" for="nominee">Nominee<span class="text-danger">&nbsp;*</span>&nbsp;:&nbsp;</label>
                            <input name="nominee" id="nominee" class="form-control" placeholder="Nominee name" type="text"/>
                            <span class="text-danger validate-err-msg" id="nominee-error"></span>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="form-label">Nominee Relation<span class="text-danger">&nbsp;*</span>&nbsp;:&nbsp;</label>
                            <select name="nominee_relation_id" id="nominee_relation_id" class="form-select">
                                <option value="">Select</option>
                                @foreach ($nominee_relations as $relation)
                                    <option value="{{ $relation->id }}">{{ $relation->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger validate-err-msg" id="nominee_relation_id-error"></span>
                        </div>
                    </div>
                    <div class="row mb-2 pt-2">
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <label class="form-label">Document Type&nbsp;:</label>
                            <select name="document_type[]" id="document_type_0" class="form-select">
                                <option value="">Select</option>
                                @foreach ($documents as $doc_val)
                                    <option value="{{ $doc_val->id }}"@selected($doc_val->id == \App\Enums\DocumentType::AADHAR->value)>{{ $doc_val->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger validate-err-msg" id="document_type_0-error"></span>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-6">
                            <label class="form-label">Documents&nbsp;:</label>
                            <div class="input-group">
                                <input type="file" name="dc_file_list[]" id="dc_file_list_0" class="form-control">
                                <span class="text-danger validate-err-msg" id="dc_file_list_0-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2 pt-2">
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <label class="form-label">Document Type&nbsp;:</label>
                            <select name="document_type[]" id="document_type_1" class="form-select">
                                <option value="">Select</option>
                                @foreach ($documents as $doc_val)
                                    <option value="{{ $doc_val->id }}">{{ $doc_val->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger validate-err-msg" id="document_type_1-error"></span>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-6">
                            <label class="form-label">Documents&nbsp;:</label>
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
                    <div class="mb-3" id="edit-error"></div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success text-bg-secondary">
                            <i class="bi bi-link-45deg" aria-hidden="true">&nbsp;</i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-file-submit', ['form' => 'edit'])
