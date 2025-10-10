{{-- Check all filter --}}
{{-- 
    This will select multiple checkboxes at a time
    @var element html class
 --}}
<script type="module">
    $(function(){
        $('#{{ $element }}_all').click(function(e){
            $('.{{ $element }}_filter').prop('checked', $(this).prop('checked'));
        });
    });
</script>