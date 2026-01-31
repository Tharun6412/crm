<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Reconnect Consumer</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Blade component --}}
            <x-consumer.basic-details :consumer="$consumer" type="2" class="bg-info-subtle" />
            <div id="reconnect-success" class="p-2">
                <h4 class="fw-semibold text-decoration-underline">Reconnection Request</h4>
                 <form id="reconnect-form" action="{{ url('consumers/reconnect/'.$consumer->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Reconnection Charges<span class="text-danger">&nbsp;*</span>&nbsp;:</label>
                        <div class="col-sm-8">
                            <select class="form-select form-select-sm" name="item_id" id="item_id">
                                <option value="">select</option>
                                @foreach ($inv_items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}&nbsp;-&nbsp;{{ $item->price }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-form-label col-sm-3 text-end">Notes<span class="text-danger">*</span>:</label>
                        <div class="col-sm-8">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="m-1" id="reconnect-error"></div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check2-square" aria-hidden="true">&nbsp;</i>Reconnect
                        </button>
                    </div>
                 </form>
            </div> 
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'reconnect'])
