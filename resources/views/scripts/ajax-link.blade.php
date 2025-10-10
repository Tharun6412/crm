{{-- Link data in response view --}}
{{-- 
    This will load the modal for the response of the link
--}}
<script type="module">
   $(function(){
        $(".ajax-link").click(function(e){
            e.preventDefault();
            $.get($(this).attr('href'), function(data) {
                $("#{{ $div }}").html(data);
            });
        });
    });
</script>