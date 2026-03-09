<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit New Scheme</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="edit-scheme-success">
                <form action="{{ url('master/consumer/schemes/'.$scheme->id) }}" id="edit-scheme-form" name="edit-scheme-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="id" name="id" value="{{ $scheme->id }}">
                    <div class="mb-2 row">
                        <label for="segment" class="col-sm-4 col-form-label text-end">Segment&nbsp;:&nbsp;<i class="text text-danger">*&nbsp;</i></label>
                        <div class="col-sm-8">
                            <select name="segment" id="segment" class="form-control">
                                <option value="">Select Segment</option>
                                @foreach ($segments as $segment)
                                    <option value="{{ $segment->id }}" @selected($segment->id == $scheme->segment_id)>{{ $segment->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <label for="connection_type" class="col-sm-4 col-form-label text-end">Connection Type&nbsp;:&nbsp;<i class="text text-danger">*&nbsp;</i></label>
                        <div class="col-sm-8">
                            <select name="connection_type" id="connection_type" class="form-control">
                                <option value="">Select Connection Type</option>
                                @foreach ($connection_types as $connection_type)
                                    <option value="{{ $connection_type->id }}" @selected($connection_type->id == $scheme->connection_type_id)>{{ $connection_type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <label for="name" class="col-sm-4 col-form-label text-end">Scheme Name&nbsp;:&nbsp;<i class="text text-danger">*&nbsp;</i></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Scheme Name" value="{{ $scheme->name }}">
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <label for="code" class="col-sm-4 col-form-label text-end">Scheme Code&nbsp;:&nbsp;<i class="text text-danger">*&nbsp;</i></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="code" name="code" placeholder="Enter Scheme Code" value="{{ $scheme->code }}">
                        </div>
                    </div>
                    {{-- <div class="mb-3 row">
                        <label for="payment" class="col-sm-2 col-form-label">Scheme Payment</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="payment" name="payment" value="{{ old('payment') }}">
                        </div>
                    </div> --}}
                    <div class="mb-2 row">
                        <label for="registration" class="col-sm-4 col-form-label text-end">Registration Amount&nbsp;:&nbsp;<i class="text text-danger">*&nbsp;</i></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="registration" name="registration" placeholder="Enter Registration Amount" value="{{ $scheme->registration }}">
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <label for="security" class="col-sm-4 col-form-label text-end">Security Deposit Amount&nbsp;:&nbsp;<i class="text text-danger">*&nbsp;</i></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="security" name="security" placeholder="Enter Security Deposit Amount" value="{{ $scheme->security }}">
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <label for="consumption" class="col-sm-4 col-form-label text-end">Consumption Deposit Amount&nbsp;:&nbsp;<i class="text text-danger">*&nbsp;</i></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="consumption" name="consumption" placeholder="Enter Consumption Deposit" value="{{ $scheme->consumption }}">
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <label for="min_payment" class="col-sm-4 col-form-label text-end">Minimum Payment Amount&nbsp;:&nbsp;<i class="text text-danger">*&nbsp;</i></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="min_payment" name="min_payment" placeholder="Enter Minimum Payment Amount" value="{{ $scheme->min_payment }}">
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <label for="emi_amount" class="col-sm-4 col-form-label text-end">EMI Amount&nbsp;:&nbsp;</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="emi_amount" name="emi_amount" placeholder="Enter EMI amount" value="{{ $scheme->emi_amount }}">
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <label for="rental_amount" class="col-sm-4 col-form-label text-end">Rental Amount&nbsp;:&nbsp;</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="rental_amount" name="rental_amount" placeholder="Enter Rental Amount" value="{{ $scheme->rental_amount }}">
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <label for="applicable_ga" class="col-sm-12 col-form-label text-start fw-bold">Applicable GAs&nbsp;:&nbsp;<i class="text text-danger">*&nbsp;</i></label>
                        <div class="col-sm-12">
                            <div class="row g-2">
                                @php
                                    $ga_selected = $scheme->gas->pluck('id')->toArray();
                                    // $ga_selected = $scheme->schemesGa->pluck('ga_id')->toArray();
                                @endphp
                                @foreach ($gas as $ga)
                                    <div class="col-3">
                                        <input class="form-check-input" id="app_ga_{{ $ga->id }}" type="checkbox" name="applicable_ga[]" value="{{ $ga->id }}" @checked(in_array($ga->id, $ga_selected))>
                                        <label class="form-check-label" for="app_ga_{{ $ga->id }}">{{ $ga->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="m-1" id="edit-scheme-error"></div>
                    <div class="row">
                        <div class="offset-sm-4 col-sm-8">
                            <button type="submit" class="btn btn-success"><i class="bi bi-save"></i>&nbsp;Update Scheme</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'edit-scheme'])