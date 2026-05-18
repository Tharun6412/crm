@php
    // $currentStatus = \App\Enums\LeadStatus::from($lead->status_id)->value;
@endphp
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Status Update - {{ $lead->code }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div id="status-success">
            <x-lms.lead-details :leads="$lead" class="bg-info-subtle"/>  
            <form id="status-form" action="{{ url('lms/leads/statusUpdate/'.$lead->id) }}" method="POST">                
                @csrf
                <div class="row mb-2">
                    <label class="col-form-label col-sm-2 text-end">Status :</label>
                    <div class="col-sm-8">
                        <select name="parent_status_id" id="parent_status_id"
                        class="form-select"
                        onchange="getChildStatus(this.value)">

                        <option value="">Select Status</option>

                        @foreach($parent_status as $parent)
                            <option value="{{ $parent->id }}"
                                @selected($parent->id == $lead->status?->parent_id)>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-form-label col-sm-2 text-end">Sub Status :</label>
                    <div class="col-sm-8">
                        <select name="status_id" id="status_id" class="form-select">
                        <option value="">Select SubStatus</option>

                        @foreach($child_status as $status)
                            <option value="{{ $status->id }}"
                                @selected($status->id == $lead->status_id)>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-form-label col-sm-2 text-end">Medium:</label>
                    <div class="col-sm-8"> 
                        <select name="lead_channel_id" class="form-select">
                            <option value="">Select Medium</option>
                            @foreach($channels as $channel)
                                <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-form-label col-sm-2 text-end">Notes&nbsp;:<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <textarea name="notes" id="notes" class="form-control"></textarea>
                        <small class="text-muted">Maximum 225 Characters Allowed</small>
                    </div>
                </div>
                <div class="mb-2" id="active-error"></div>  
                <div class="row ">
                    <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-save" aria-hidden="true">&nbsp;</i>Update Status</button>
                    </div>
                </div> 
                <x-lms.statushistory :leads="$lead" class="bg-secondary-subtle" />            
            </form>
            </div>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit',['form' => 'status'])
<script>

function getChildStatus(parent_id)
{
    $('#status_id').empty();
    let options = '<option value="">Select Status</option>';
    $.get("{{ url('lms/leads/getChildStatus') }}",{ 'parent_id' : parent_id },function(data){
        if(data.child_status && data.child_status.length > 0){
            data.child_status.forEach(function(status){
                options += `<option value="${status.id}">${status.name}</option>`;
            });
        }
        $('#status_id').html(options);
    });
}
</script>