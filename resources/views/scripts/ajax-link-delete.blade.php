{{-- AJAX link delete --}}
{{-- 
    This will create a DELETE request to the server with the given link
    The link has to be with the calss ajax-link-delete
    Example:
    <a href="delete url" class="ajax-link-delete">Delete</a>
 --}}
<script type="module">
    $(function(){
        $('.ajax-link-delete').click(function(e){
            e.preventDefault();
            let element = this;
            if(confirm('Are you sure you want to delete?')) {
                $.ajax({
                    url: $(element).attr('href'),
                    type: 'DELETE',
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