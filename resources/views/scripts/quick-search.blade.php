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
                            data.forEach(item => {
                                html += `
                                    <div class="list-group-item bg-light">
                                        <div><span class="text-warning-emphasis fw-semibold"><i class="bi bi-person"></i> ${item.crn}</span>, <span class="text-success-emphasis fw-semibold">${item.name}</span>, <span class="text-dark fw-semibold"><i class="bi bi-geo-alt"></i> ${item.ga.name}</span>, <span class="text-dark fw-semibold"><i class="bi bi-check2-square"></i> ${item.status.name}</span></div>
                                        <div class="p-1 fs-sm">
                                        <a href="consumers/${item.id}" class="link-primary me-2" target="_blank"><i class="bi bi-person-lines-fill"></i>&nbsp;Details</a>
                                        <a href="bill/gasInvoice/create/${item.id}" class="link-primary me-2" target="_blank"><i class="bi bi-receipt"></i>&nbsp;Gas Bill</a>
                                        <a href="bill/invoice/create/${item.id}" class="link-primary me-2" target="_blank"><i class="bi bi-file-ruled"></i>&nbsp;Add Invoice</a>
                                        </div>
                                    </div>`;
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