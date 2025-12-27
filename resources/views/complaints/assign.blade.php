{{-- Complaint Assign --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Assign Complaint&nbsp;#{{ $complaint->code }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Consumer + Complaint Details --}}
            <div>
                <x-consumer.complaint-details :complaint="$complaint" class="bg-info-subtle"/>
            </div>
            <div id="assign-success" class="mt-3">
                <form id="assign-form" action="{{ url('calls/assignTo/'.$complaint->id) }}" method="POST">
                    @csrf
                    {{-- Complaint Assign To Dropdown List --}}
                    <div class="row mb-3">
                        <label class="col-form-label col-sm-2 text-end">Assign To&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <select class="form-select form-select-sm" name="assign_id" id="assign_id">
                                <option value="">select</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->first_name }}&nbsp;{{ $user->last_name }} - {{ $user->emp_id }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-2 text-end">Notes&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="m-1" id="assign-error"></div>
                    <div class="row">
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-check2-square" aria-hidden="true">&nbsp;</i>Assign
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'assign'])
