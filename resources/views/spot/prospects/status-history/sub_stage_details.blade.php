<div>
    @php
        $sub_stage_id_val = request()->has('sub_stage_id') ? request()->get('sub_stage_id') : $prospect->stage_id; 
    @endphp
    @switch($sub_stage_id_val)
        @case(7)
        @case(8)
        @case(9)
        @case(10)
        @case(13)
        @case(14)
        @case(15)
            @break
        @case(11)
        @case(12)
            @if ($sub_stage_id_val == 11)
                <div class="row mb-1">
                    <label for="expected_date" class="col-form-label col-sm-4 text-end">
                        Expected Date <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <input type="text" name="expected_date" id="expected_date" class="form-control form-control-sm" value="{{ $prospect->expected_date->format('d-m-Y') }}" placeholder="DD-MM-YYYY"/>
                            <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                        </div>
                        <span class="text-danger" id="expected_date-error"></span>
                    </div>
                </div>
            @endif
            <div class="row mb-1">
                <label for="dc_file" class="col-form-label col-sm-4 text-end">
                    Document <span class="text-danger">*</span>:
                </label>
                <div class="col-sm-6">
                    <input type="file" class="form-control form-control-sm" name="dc_file" id="dc_file"/>
                    <small class="text-secondary">Note: Upload only PDF, JPG, JPEG, or PNG files (Max size: 20MB).</small>
                    <span id="dc_file-error" class="text-danger"></span>
                </div>
            </div>
            @break
        @case(16)
            <div class="row mb-1">
                <label for="offer-document" class="col-form-label col-sm-4 text-end">
                    Offer Document <span class="text-danger">*</span>
                </label>
                <div class="col-sm-6">
                    <select name="offer-document" id="offer-document" class="form-select form-select-sm">
                        <option value="">Select Offer</option>
                        @foreach ($offer_type_docs as $doc_value)
                            <option value="{{ $doc_value->id }}">Offer&nbsp;-&nbsp;{{ $doc_value->offer_count }}&nbsp;({{ $doc_value->file->file_name }})</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="offer-document-error"></span>
                </div>
            </div>
        @break
        @default
            
    @endswitch
</div>