{{-- Add Pipeline Form --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Pipeline Details</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="add-pipeline-success">
                <form action="{{ url('spot/prospect/pipeline/'.$id) }}" method="POST" id="add-pipeline-form">
                    @csrf
                    @method('PUT')
                    <table class="table table-bordered table-secondary" id="pipeline-table">
                        <thead class="table-secondary">
                            <tr>
                                <th>Pipe Type</th>
                                <th class="text-end">Length</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pipeline_list as $key => $pipeline)
                                <tr>
                                    <td>
                                        <select class="form-select form-select-sm" name="pipe_type_id[]">
                                            <option value="">Select Pipe Type</option>
                                            @foreach($pipe_types as $type)
                                                <option value="{{ $type->id }}" 
                                                        {{ $type->id == $pipeline->pipe_type_id ? 'selected' : '' }}>
                                                    {{ $type->name }} - ({{ $type->size }}mm)
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="length[]" class="form-control form-control-sm text-end" 
                                            value="{{ $pipeline->length }}">
                                    </td>
                                    <td class="text-center">
                                        @if ($key == 0)
                                            <button type="button" class="btn btn-outline-success add-row"><i class="bi bi-plus-square"></i></button>
                                        @else
                                            <button type="button" class="btn btn-outline-danger remove-row">
                                                <i class="bi bi-dash-square"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                            <!-- Show one blank row if no records -->
                                <tr>
                                    <td>
                                        <select class="form-select form-select-sm" name="pipe_type_id[]">
                                            <option value="">Select Pipe Type</option>
                                            @foreach($pipe_types as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }} - ({{ $type->size }}mm)</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" name="length[]" class="form-control form-control-sm text-end"></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-success add-row"><i class="bi bi-plus-square"></i></button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mb-3" id="add-pipeline-error"></div>
                    <div class="row">
                        <div class="offset-sm-3 col-sm-8">
                            <button type="submit" class="btn btn-success"><i class="bi bi-plus-square"></i>&nbsp;Add Pipeline</button>
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
{{-- Load JS Files --}}
@include('scripts.ajax-form-submit', ['form' => 'add-pipeline'])

<script type="module">
    $(function() {
        // Add new rows
        $('.add-row').off('click').on('click', function() {
            let row = $(this).closest('tr');
            let clonedRow = row.clone();
            // Reset input values
            clonedRow.find('input').val('');
            clonedRow.find('select').val('');

            // Change "+" to "-" and class to remove
            clonedRow.find('.add-row')
                .removeClass('btn-success add-row')
                .addClass('btn-outline-danger remove-row')
                .html('<i class="bi bi-dash-square"></i>').on('click', function() {
                    $(this).closest('tr').remove();
                    refreshPipeTypeOptions();
                });
            // Append the new row
            $('#pipeline-table tbody').append(clonedRow);
            refreshPipeTypeOptions();
        });

        // When dropdown changes
        $(document).on('change', 'select[name="pipe_type_id[]"]', function () {
            refreshPipeTypeOptions();
        });

        // Function to prevent duplicate pipe type selection
        function refreshPipeTypeOptions() {
            let selectedValues = [];

            $('select[name="pipe_type_id[]"]').each(function () {
                let value = $(this).val();
                if (value) {
                    selectedValues.push(value);
                }
            });

            // Enable all options first
            $('select[name="pipe_type_id[]"] option').prop('disabled', false);

            // Disable already selected options in other dropdowns
            $('select[name="pipe_type_id[]"]').each(function () {
                let currentSelect = $(this);
                let currentValue = currentSelect.val();

                selectedValues.forEach(function (value) {
                    if (value !== currentValue) {
                        currentSelect.find('option[value="' + value + '"]').prop('disabled', true);
                    }
                });
            });
        }
    });
</script>