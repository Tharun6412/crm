{{-- Link-modalv view --}}
<script type="module">
    $(function(){
        // Link to modal
        $(".link-modal").off('click').on('click', function(e){
            e.preventDefault();
            $.get($(this).attr('href'), function(data) {
                loadModal(data);
            });
        });
    });
</script>