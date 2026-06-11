{{-- Edit Ticket --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-body-secondary">
            <h3 class="modal-title">Edit Ticket</h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            
            <div id="ticket-success"> 
                <form id="ticket-form" action="{{ url('tickets/update/'.$ticket->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div>
                        <x-consumer.basic-details :consumer="$ticket->consumer" class="bg-info-subtle" />
                    </div>
                    <h4 class="mt-2">Ticket Details :</h4>
                    <div class="row mb-2">
                        <label for="category_id" class="col-form-label col-sm-2 text-end">Category&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                        <select id="category_id" name="category_id" class="form-select">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category )
                                <option value="{{ $category->id }}" @selected($ticket->category_id == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-2 text-end">Description&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <textarea name="description" id="description" class="form-control">{{ $ticket->description }}</textarea>
                            <small class="text-muted">Maximum 225 Characters Allowed</small>
                        </div>
                    </div>
                    <div id="ticket-error"></div>
                    <div class="row">
                        <div class="col-sm-8 offset-sm-2 text-end">
                            <button type="submit" class="btn btn-success"><i class="bi bi-edit" aria-hidden="true">&nbsp;</i>Update</button>
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
@include('scripts.ajax-form-submit', ['form' => 'ticket'])
