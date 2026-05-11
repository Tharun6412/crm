@php
    $status = \App\Enums\GeyserStatus::from($status_id)->name;
@endphp
{{-- Geyser Execute --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">{{ $status }} - &nbsp;{{ $geyser->code }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Geyser Details --}}
            <div>
                <x-geyser.geyser-details :geyser="$geyser" class="bg-info-subtle"/>
            </div>

            <div id="active-success" class="mt-3">
                <h4>{{ $status }}</h4>
                <form id="active-form" action="{{ url('consumers/geysers/statusChange/'.$geyser->id.'/'.$status_id) }}" method="POST">
                    @csrf
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-2 text-end">Notes&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                            <small class="text-muted">Maximum 225 Characters Allowed</small>
                        </div>
                    </div>
                    <div class="mb-2" id="active-error"></div>
                    <div class="row">
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-save" aria-hidden="true">&nbsp;</i>{{ $status }}</button>
                        </div>
                    </div>
                </form>
            </div>
            <div>
                <x-geyser.statushistory :geyser="$geyser" class="bg-info-subtle"/>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'active'])
