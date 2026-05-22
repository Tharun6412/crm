{{-- User Charge Areas Manage --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Manage ChargeArea - {{ $user->emp_id }}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {{-- Body --}}
        <div class="modal-body">
            <div id="user-success">
                <form id="user-form" action="{{ url('admin/users/updateUserCas/' . $user->id) }}"method="POST">
                    @csrf
                    @php
                        $userCa = $user->cas->pluck('id')->toArray();
                    @endphp
                    <div class="row">
                        <div class="col-sm-12">
                            @if ($cas->count() > 0)
                                @foreach ($cas as $gaName => $chargeArea)
                                    <div class="border rounded mb-3">
                                        {{-- GA Name --}}
                                        <div class="bg-light border-bottom p-2 fw-semibold"><i class="bi bi-pin-map-fill text-primary me-1"></i>{{ $gaName }}</div>
                                        {{-- Charge Areas --}}
                                        <div class="p-3">
                                            <div class="row row-cols-1 row-cols-md-3">
                                                @foreach ($chargeArea as $ca)
                                                    <div class="col mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="ca_id[]" value="{{ $ca->id }}" id="ca_{{ $ca->id }}"@checked(in_array($ca->id, $userCa))>
                                                            <label class="form-check-label" for="ca_{{ $ca->id }}">{{ $ca->name }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-3">
                                    <span class="text-danger">No Charge Areas Found</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3" id="user-error"></div>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i>&nbsp;Update Charge Areas</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x"></i>&nbsp;Close
            </button>
        </div>
    </div>
</div>

@include('scripts.ajax-file-submit', ['form' => 'user'])