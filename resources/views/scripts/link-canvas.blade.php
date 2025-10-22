{{-- Link Canvas view --}}
<script type="module">
    $(function(){
        // Link to canvas
        $(".link-canvas").click(function(e){
            e.preventDefault();
            $.get($(this).attr('href'), function(data) {
                loadCanvas(data);
            });
        });
    });
</script>