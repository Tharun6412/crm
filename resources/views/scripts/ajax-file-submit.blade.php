{{-- Ajax File submission --}}
<script type="module">
    $(function() {
        $('#{{ $form }}-form').submit(function(e){
            e.preventDefault();
            $('.validate-err-msg').html('');
            var formData = new FormData(this);
            $.ajax({
                url : $(this).attr('action'),
                type:'POST',
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success:function(data){
                    {{ $callback ?? '' }}
                    if(data.success) {
                        $('#{{ $form }}-success').html('<div class="alert alert-success mb-0">' + data.success + '</div>');
                    }
                    if($('.current-page-reload').length > 0) {
                        let link = $('#current-page').attr('href');
                        if(link) {
                            $.get(link, function(data){
                                $('.current-page-reload').html(data);
                            });
                        }
                    }
                    // Callback if applicable
                },
                error: function(data){
                    var errors = data.responseJSON.errors;
                    // $.each( errors, function( key, value ) {
                    //     $('#'+key+'-error').html(value[0]);
                    // });
                    // New Approach for array validation
                    $.each(errors, function(key, value) {
                        let safeKey = key.replace(/\./g, '_');
                        $('#' + safeKey + '-error').html(value[0]);
                    });
                }
            });
        });
    })
</script>