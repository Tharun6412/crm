{{-- Ajax form submission GET mothod --}}
{{-- 
    @var Form id, load div id are in sequence
    Example:
        lets say add-tax is the string passing to this view then
        From ID:        add-tax-form
        Output          add-tax-load
 --}}

<script type="module">
    $(function(){
        $("#{{ $form }}-form").submit(function(e){
            e.preventDefault();
            $.get($(this).attr('action'), $(this).serializeArray(), function(response){
                $('#{{ $form }}-loader').html(response);
            }).fail(function(response){
                $('#{{ $form }}-loader').html('<div class="alert alert-danger mb-0">' + response.responseJSON.message + '</div>');
            });
        });
    });
</script>