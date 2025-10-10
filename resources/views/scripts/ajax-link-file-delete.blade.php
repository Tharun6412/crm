{{-- AJAX link delete in Modal --}}
{{-- 
    This will create a DELETE request to the server with the given link
    The link has to be with the calss ajax-link-delete
    Example:
    <a href="delete url" class="ajax-link-delete">Delete</a>
 --}}
 <script type="module">
    $(function(){
        $('.ajax-link-file-delete').click(function(e){
            e.preventDefault();
            if(confirm('Are you sure you want to delete?')) {
                $.ajax({
                    url: $(this).attr('href'),
                    type: 'DELETE',
                    data: {'_token': "{{ csrf_token() }}"},
                    success: function(response){
                        // Callback Function to Reload
                        {{ $callback ?? '' }}
                        if($('.current-page-reload').length > 0) {
                            let link = $('#current-page').attr('href');
                            if(link) {
                                $.get(link, function(data){
                                    $('.current-page-reload').html(data);
                                });
                            }
                        }
                    }
                });
            }
        });
    });
</script>