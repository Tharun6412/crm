{{-- Bootstrap popover --}}
{{-- 
    Just call this page when using popovers
--}}
<script type="module">
    $(function(){
        // Popover list for notes
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl, {html: true}))
    });
</script>