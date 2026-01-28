{{-- Session files, shows the selected or uploaded files stored in session --}}

<div class="w-50">
    @if ($dc_files)
    <ul class="list-group mb-2">
        @foreach ($dc_files as $item)
        <li class="list-group-item" id="dc_fi_{{ $item['id']}}">
            <i class="bi bi-file-earmark-check"></i>&nbsp;{{ $item['doc_number']}}&nbsp;[{{ $item['file_name'] }}]
            <button type="button" class="btn btn-outline-danger btn-sm float-end" onclick="deleteSessionFile({{ $item['id'] }})"><i class="bi bi-trash"></i></button>
        </li>
        @endforeach
    </ul>
    @else
        <div class="alert alert-warning">No files addedd!</div>    
    @endif
</div>
<script>
// Delete session file
function deleteSessionFile(id) {
    $.get(WEBROOT + '/dc/deleteFile', {'id': id}, function() {
        $("#dc_fi_"+id).remove();
    });
}
</script>