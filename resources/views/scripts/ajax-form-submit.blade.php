{{-- Ajax form submission --}}
{{-- 
    @var Form id, error div id and success response div id are in sequence
    @var close_model bool
    Example:
        lets say add-tax is the string passing to this view then
        From ID:        add-tax-form
        Error div ID:   add-tax-error
        Success div ID: add-tax-success
        callbak

 --}}

<script type="module">
    $(function(){
        $("#{{ $form }}-form").submit(function(e){
            e.preventDefault();
            $.post($(this).attr('action'), $(this).serializeArray(), function(response){
                $('#{{ $form }}-success').html('<div class="alert alert-success mb-0">' + response.success + '</div>');
                // Reload the back page if applicable
                if($('.current-page-reload').length > 0) {
                    let link = $('#current-page').attr('href');
                    if(link) {
                        $.get(link, function(data){
                            $('.current-page-reload').html(data);
                        })
                    }
                }
                // Callback if applicable
                {{ $callback ?? '' }}
            }).fail(function(response){
                $('#{{ $form }}-error').html('<div class="alert alert-danger mb-0">' + response.responseJSON.message + '</div>');
            });
        });
    });
</script>