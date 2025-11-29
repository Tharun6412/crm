<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add New Scheme</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="add-scheme-success">
                <form action="{{ url('master/consumer/schemes') }}" id="add-scheme-form" name="add-scheme-form" method="post">
                    @csrf
                    <div class="mb-3 row">
                        <label for="segment" class="col-sm-4 col-form-label">Segment&nbsp;:&nbsp;<i class="text text-danger">*&nbsp;</i></label>
                        <div class="col-sm-8">
                            <select name="segment" id="segment" class="form-control">
                                <option value="">Select Segment</option>
                                @foreach ($segments as $segment)
                                    <option value="{{ $segment->id }}">{{ $segment->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="name" class="col-sm-4 col-form-label">Scheme Name&nbsp;:&nbsp;<i class="text text-danger">*&nbsp;</i></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Scheme Name">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="code" class="col-sm-4 col-form-label">Scheme Code&nbsp;:&nbsp;<i class="text text-danger">*&nbsp;</i></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="code" name="code" placeholder="Enter Scheme Code">
                        </div>
                    </div>
                    {{-- <div class="mb-3 row">
                        <label for="payment" class="col-sm-2 col-form-label">Scheme Payment</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="payment" name="payment" value="{{ old('payment') }}">
                        </div>
                    </div> --}}
                    <div class="mb-3 row">
                        <label for="registration" class="col-sm-4 col-form-label">Registration Amount&nbsp;:&nbsp;<i class="text text-danger">*&nbsp;</i></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="registration" name="registration" placeholder="Enter Registration Amount">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="security" class="col-sm-4 col-form-label">Security Deposit Amount&nbsp;:&nbsp;<i class="text text-danger">*&nbsp;</i></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="security" name="security" placeholder="Enter Security Deposit Amount">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="consumption" class="col-sm-4 col-form-label">Consumption Deposit Amount&nbsp;:&nbsp;<i class="text text-danger">*&nbsp;</i></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="consumption" name="consumption" placeholder="Enter Consumption Deposit">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="min_payment" class="col-sm-4 col-form-label">Minimum Payment Amount&nbsp;:&nbsp;<i class="text text-danger">*&nbsp;</i></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="min_payment" name="min_payment" placeholder="Enter Minimum Payment Amount">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="emi_amount" class="col-sm-4 col-form-label">EMI Amount&nbsp;:&nbsp;</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="emi_amount" name="emi_amount" placeholder="Enter EMI amount">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="rental_amount" class="col-sm-4 col-form-label">Rental Amount&nbsp;:&nbsp;</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="rental_amount" name="rental_amount" placeholder="Enter Rental Amount">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="applicable_ga" class="col-sm-4 col-form-label">Applicable GAs&nbsp;:&nbsp;<i class="text text-danger">*&nbsp;</i></label>
                        <div class="col-sm-8">
                            @foreach ($gas as $ga)
                                <label class="me-3">
                                    <input type="checkbox" name="applicable_ga[]" value="{{ $ga->id }}">
                                    {{ $ga->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-3" id="add-scheme-error"></div>
                    <button type="submit" class="btn btn-success"><i class="bi bi-plus-square"></i>&nbsp;Add Scheme</button>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'add-scheme'])