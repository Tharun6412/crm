<div class="bd-callout bd-callout-success bg-transparent card mt-0 border-success mb-3" id="edit-status-success">
    <form id="edit-status-form" action="{{ url('spot/prospects/updateStatus/'.$id) }}"  class="form-horizontal" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row mb-1">
            <h5 class="modal-title">Prospect Status Update</h5>
            <label for="stage_id" class="col-form-label col-sm-4 text-end">Stage&nbsp;<span class="text-danger">*</span>&nbsp;:</label>
            <div class="col-sm-6">
                <select id="stage_id" name="stage_id" class="form-select form-select-sm" onchange="getSubStagesByStage(this.value)">
                    <option value="">select stage</option>
                        @foreach ($status_list as $type)
                            <option value="{{ $type->id }}" @selected($type->id == $prospect->stage)>{{ $type->name }}</option>
                        @endforeach
                </select>
                <span class="text-danger" id="stage_id-error"></span>
            </div>
        </div>
        <div class="row mb-1 ">
            <label for="sub_stage_id" class="col-form-label col-sm-4 text-end">Sub Stage&nbsp;<span class="text-danger">*</span>&nbsp;:</label>
            <div class="col-sm-6">
                <select id="sub_stage_id" name="sub_stage_id" class="form-select form-select-sm" onchange="getDetailsBySubStage(this.value, {{ $prospect->id }})">
                    <option value="">select sub stage</option>
                        @foreach ($sub_stages as $stage)
                            <option value="{{ $stage->id }}" @selected($stage->id == $prospect->sub_stage_id)>{{ $stage->name }}</option>
                        @endforeach
                </select>
                <span class="text-danger" id="sub_stage_id-error"></span>
            </div>
        </div>
        <div class="row mb-1">
            <div id="subStages_body">
                @include('spot.prospects.status-history.sub_stage_details')
            </div>
        </div>
        <div class="row mb-2">
            <label class="col-form-label col-sm-4 text-end">Notes&nbsp;<span class="error text-danger">*</span>&nbsp;:</label>
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <textarea name="notes" id="notes" class="form-control form-control-sm" placeholder="Enter Notes"></textarea>
                </div>
                <span class="text-danger" id="notes-error"></span>
            </div>
        </div>
        <div class="row mb-0">
            <label class="col-form-label col-sm-4 text-end">&nbsp;</label>
            <div class="col-md-6">
                <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-save"></i>&nbsp;Update Status</button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="$('#action-type').html('')"><i class="bi bi-x-lg"></i>&nbsp;Close</button>
            </div>
        </div>
    </form>
</div>
@include('scripts.datepicker', ['list' => ['expected_date']])
@include('scripts.ajax-file-submit', ['form' => 'edit-status', 'callback' => 'reloadStatusHistory()'])
