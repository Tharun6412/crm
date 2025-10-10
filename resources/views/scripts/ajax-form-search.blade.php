{{-- Ajax form search --}}
{{-- 
    Form id, response div id are in sequence
    Example:
        lets say user is the string passing to this view then
        From ID:            user-search-form
        Response div ID:    user-list

 --}}
 <script type="module">
    $(function(){
        // Search
        $("#{{ $form }}-search-form").submit(function(e) {
            e.preventDefault();
            $.get($(this).attr('action'), $(this).serializeArray(), function(response) {
                $("#{{ $form }}-list").html(response);
            }).fail(function(response){
                $('#{{ $form }}-list').html('<div class="alert alert-danger">' + response.responseJSON.message + '</div>');                
            });
        });
    });
</script>