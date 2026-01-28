// Document load
$(function(){
    $("#dc-file-uploader-form").on("submit", function(e) {
        e.preventDefault();
        var form_data = new FormData(this);
        
        // Upload
        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        var percentComplete = Math.round((e.loaded / e.total) * 100);
                        $('#progress-bar').width(percentComplete + '%');
                        $('#progress-bar').html(percentComplete + '%');
                        $('#progress-bar').attr('aria-valuenow', percentComplete);
                    }
                }, false);
                return xhr;
            },
            url: $(this).attr('action'),
            type: 'POST',
            data: form_data,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#dc-file-uploader-msg').html('<div class="alert alert-success">'+ response.success +'</div>');
                $("#dc-file-uploader-form")[0].reset();
                resetDcProgressBar();
            },
            error: function(response) {
                // console.log(response);                    
                resetDcProgressBar();
                $('#dc-file-uploader-msg').html('<div class="alert alert-danger">'+ response.responseJSON.message +'</div>');
            }
        });
    });
});
// Reset upload progress bar
function resetDcProgressBar() {
    setTimeout(function(){
        $('#progress-bar').width('0%');
        $('#progress-bar').html('0%');
        $('#progress-bar').attr('aria-valuenow', 0);
    }, 1000);
}