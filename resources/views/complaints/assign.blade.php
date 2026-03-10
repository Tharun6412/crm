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
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Department&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <select class="form-select form-select-sm" name="department_id" id="department_id" onchange="deptUsersList(this.value)">
                                <option value="">select</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Assign To&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <select class="form-select form-select-sm" name="assign_id" id="assign_id">
                                <option value="">select</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Notes&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="m-1" id="assign-error"></div>
                    <div class="row">
                        <div class="offset-sm-3 col-sm-7">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-person-check" aria-hidden="true">&nbsp;</i>Assign
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
<script type="text/javascript">
    // Get Users List By Department
    function deptUsersList(dept_id)
    {
        $.get("{{ url('calls/usersListByDepartment') }}", {'department_id' : dept_id }, function(data) {
            $('#assign_id').empty();
            let options = '<option value = "">Select</option>'
            if(data.users && data.users.length > 0) {
                data.users.forEach(function(user) {
                    options += `<option value="${user.id}">${user.name}</option>`;
                });
            }
            $('#assign_id').html(options);
        });
    }
</script>