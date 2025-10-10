{{-- AJAX link delete --}}
{{-- 
    This will create a DELETE request to the server with the given link
    The link has to be with the calss ajax-link-delete
    Example:
    <a href="delete url" class="ajax-link-delete">Delete</a>
 --}}
<script type="module">
    $(function(){
        $("#{{ $mod ?? 'none' }}").click(function(e){
            e.preventDefault();
            if(confirm("{{ $msg ?? '' }}")) {
                $.ajax({
                    url: $(this).attr('href'),
                    type: 'PUT',
                    data: {'_token': "{{ csrf_token() }}"},
                    success: function(response){
                        alert(response.msg);
                        {{ $callback ?? '' }}
                    }
                });
            }
        });
    });
</script>