<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-body-secondary">
            <h4 class="modal-title">Assign Team</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div id="team-success">
                <form id="team-form" action="{{ url('consumers/waiting/pending-consumers/store/'.$team_consumer->id) }}" method="POST">
                    @csrf
                    {{-- @method('PUT') --}}
                    <div class="row mb-3 align-items-center">
                        <label for="team_id" class="col-md-3 col-form-label text-md-end">Select Team :</label>
                        <div class="col-md-6">
                            <select name="team_id" id="team_id" class="form-select" onchange="getEmployeesByTeam(this.value)">
                                <option value=""> Select Team </option>
                                @foreach ($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->name }} - {{ $team->departments->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label for="team_id" class="col-md-3 col-form-label text-md-end">Select Employee :</label>
                        <div class="col-md-6">
                            <select name="assign_id" id="assign_id" class="form-select">
                                <option value="">Select Employee</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-2" id="team-error"></div>  
                    <div class="row mt-3">
                        <div class="col-sm-12 text-center">
                            <button type="submit" class="btn btn-success btn-md"><i class="bi bi-save" aria-hidden="true">&nbsp;</i>Assign</button>
                        </div>
                    </div>
                </div> 
                </form>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
        </div> 
    </div>
</div>
@include('scripts.ajax-form-submit',['form' => 'team'])
<script type="text/javascript">
    // Get Team Employees
    function getEmployeesByTeam(team_id)
    {
        $.get("{{ url('consumers/waiting/pending-consumers/getEmployeesByTeam') }}", {'team_id' : team_id}, function(data) {
            $('#assign_id').empty();
            let options = '<option value = "">Select Employee</option>'
            if(data.users && data.users.length > 0) {
                data.users.forEach(function(user) {
                    options += `<option value="${user.id}">${user.name}</option>`;
                });
            }
            $('#assign_id').html(options);
        });
    }
</script>