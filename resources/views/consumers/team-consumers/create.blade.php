<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Assign Team</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div id="team-success">
                <form id="team-form" action="{{ url('consumers/waiting/pending-consumers/'.$team_consumer->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3 align-items-center">
                        <label for="team_id" class="col-md-3 col-form-label text-md-end">Select Team :</label>
                        <div class="col-md-6">
                            <select name="team_id" id="team_id" class="form-select">
                                <option value=""> Select Team </option>
                                @foreach ($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->name }} - {{ $team->departments->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-2" id="active-error"></div>  
                    <div class="row ">
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-save" aria-hidden="true">&nbsp;</i>Assign</button>
                        </div>
                    </div>
                </div> 
                </form>
            </div>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit',['form' => 'team'])