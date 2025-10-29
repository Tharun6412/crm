{{-- Manage Add/Edit Targets Data --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Manage Targets Data - {{ $ga->name }}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="manage-target-success">
                <form action={{ url('spot/targets/manageTargetData/'.$ga->id) }} method="post" id="manage-target-form">
                    @csrf
                    <div class="container overflow-hidden">
                        <div class="row gy-5">
                            <div class="col-2">
                                <div class="p-3 border bg-success">Year</div>
                            </div>
                            @foreach ($segments as $segment)
                                <div class="col-5">
                                    <div class="p-3 border bg-success text-center">{{ $segment->code }}</div>
                                </div>
                            @endforeach
                        </div>
                        <br/>
                        @for ($date = $y_start->copy(); $date->lte($y_end); $date->addMonth())
                            <div class="row gy-5">
                                <div class="col-2">
                                    <div class="p-3 border bg-light">{{ $date->format('M-y') }}</div>
                                </div>
                                @foreach ($segments as $segment)
                                    @php
                                        $target_data_val = $target_data->where('ga_id', $ga->id)->where('segment_id', $segment->id)->where('target_date', $date->copy()->startOfMonth()->toDateString())->first();
                                    @endphp
                                    <div class="col-5">
                                        <div class="p-3 bg-light">
                                            <div class="input-group">
                                                <input type="text" name="target_value[{{ $date->format('m-Y') }}][{{ $segment->id }}]" id="target_value[{{ $date->format('m-Y') }}][{{ $segment->id }}]" class="form-control text-end" value="{{ $target_data_val->target_value ?? '' }}"/>
                                                <span class="input-group-text">SCMD</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endfor
                    </div>
                    <div class="mb-3" id="manage-target-error"></div>
                    <div class="row">
                        <div class="offset-sm-3 col-sm-8">
                            <button type="submit" class="btn btn-success"><i class="bi bi-plus-square"></i>&nbsp;Add/Edit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'manage-target', 'callback' => 'reloadTargets()'])

