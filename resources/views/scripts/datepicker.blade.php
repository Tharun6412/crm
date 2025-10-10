{{-- Scripts Datepicker --}}
{{-- 
    This will load the datepickers
    @var array html element ids
--}}
<script type="module">
    $(function(){
        @foreach ($list as $item)
            $('#{{ $item }}').datepicker({format: 'dd-mm-yyyy', autoHide: true});
        @endforeach
    });
</script>