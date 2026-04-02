{{-- Quick Search --}}
<script type="module">
    $(function () {
        // Quick search
        let timer;
        $('#quickSearch').on('keyup', function (e) {
            clearTimeout(timer);
            
            // Dont process for any additional key
            if ((/^[a-zA-Z0-9\s]*$/.test(this.value)) && (e.key.length === 1 || e.keyCode == 13)) { }
            else {
                return;
            }

            let query = $(this).val();
            if (query.length < 3) {
                $("#qs-clr").addClass('d-none');
                $('#searchResults').hide().empty();
                return;
            }
            $("#qs-clr").removeClass('d-none');
            // Wait for few seconds
            timer = setTimeout(() => {
                $.ajax({
                    url: "{{ url('consumers/search') }}",
                    type: "GET",
                    data: { q: query },
                    success: function (data) {
                        let html = '';

                        if (data.length === 0) {
                            html = '<div class="list-group-item">No results found</div>';
                        } else {
                            console.log(data);
                            
                            data.forEach(item => {
                                html += `<div class="row bg-body border border-bottom m-0 g-0 p-1 rounded-3">
                                        <div class="col-9 block-height"><a href="consumers/${item.id}" title="Details" class="mt-3" target="_blank"><div class="p-2 mt-2"><i class="bi bi-${item.connection_type_id == 1 ? 'speedometer2' : 'wifi'}"></i>&nbsp;${item.crn}, ${item.name}, ${item.ga.name}, ${item.status.name}</div></a></div><div class="col-1 bg-primary-subtle text-center"><a href="consumers/${item.id}" title="Details" class="p-2 fs-4 mt-1 block-height" target="_blank"><i class="bi bi-person-lines-fill"></i></a></div><div class="col-1 bg-success-subtle text-center"><a href="bill/gasInvoice/create/${item.id}" title="Generate Gas Bill" class="p-2 fs-4 mt-1 block-height" target="_blank"><i class="bi bi-receipt"></i></a></div><div class="col-1 bg-danger-subtle text-center"><a href="bill/invoice/create/${item.id}" title="Create Invoice" class="p-2 fs-4 mt-1 block-height" target="_blank"><i class="bi bi-file-ruled"></i></a></div></div>`;
                            });
                        }
                        $('#searchResults').html(html).show();
                    }
                });
            }, 1000); // debounce
        });

        $("#qs-clr").on('click', function(e){
            $('#quickSearch').val('');
            $(this).addClass('d-none');
        });

        // Hide results when clicking outside
        $(document).on('click', function () {
            $('#searchResults').hide();
        });
    });
</script>
<style> .block-height {height: 60px; display: inline-block;overflow: hidden;} .block-height:hover {background-color:rgb(241 241 241)} a.block-height:hover {background:none;} </style>