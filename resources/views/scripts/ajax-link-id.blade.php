{{-- Link data in response view --}}
{{-- 
    This will load the modal for the response of the link
--}}
<script type="module">
   $(function(){
        $("#{{ $mod ?? 'none' }}-link").click(function(e){
            e.preventDefault();
            $.get($(this).attr('href'), function(data) {
                $("#{{ $cancel ?? 'none' }}").html('');
                $("#{{ $div ?? 'none' }}").html(data);
            });
        });
    });
</script>