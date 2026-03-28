{{-- Manage Add/Edit Targets Data --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Manage Targets Data - {{ $ga->name }} (FY {{ $y_start->format('Y') }} - {{ $y_end->format('Y') }})</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="manage-target-success">
                <form action={{ url('spot/targets/manageTargetData/'.$ga->id) }} method="post" id="manage-target-form" class="mb-0">
                    @csrf
                    <table class="table table-bordered table-light table-hover mb-0">
                        <thead class="table-success">
                            <tr>
                                <td class="text-end">Month - Year</td>
                                @foreach ($segments as $segment)
                                    <td class="text-end">{{ $segment->code }} Target</td>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @for ($date = $y_start->copy(); $date->lte($y_end); $date->addMonth())
                                <tr>
                                    <td class="table-light text-end">{{ $date->format('M-y') }}</td>
                                    @foreach ($segments as $segment)
                                        @php
                                            $target_data_val = $target_data->where('ga_id', $ga->id)->where('segment_id', $segment->id)->where('target_date', $date->copy()->startOfMonth()->toDateString())->first();
                                        @endphp
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <input type="text" name="target_value[{{ $date->format('m-Y') }}][{{ $segment->id }}]" id="target_value[{{ $date->format('m-Y') }}][{{ $segment->id }}]" class="form-control text-end" value="{{ $target_data_val->target_value ?? '' }}"/>
                                                <span class="input-group-text bg-info-subtle">SCMD</span>
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    <div class="m-1" id="manage-target-error"></div>
                    <div class="text-end p-2">
                        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i>&nbsp;Save</button>
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

