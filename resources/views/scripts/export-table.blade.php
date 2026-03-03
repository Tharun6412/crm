{{-- 
    Universal Table Export Script

    Usage Scenarios:

    1️⃣ Without Tabs
        @include('scripts.export-table', [
            'button' => 'exportBtn',
            'table'  => 'consumerTable'
        ])

    2️⃣ With Tabs (exports active tab table automatically)
        @include('scripts.export-table', [
            'button' => 'exportBtn',
            'tabBased' => true
        ])

    Optional:
        'filename' => 'Consumers_Report'
        'sheet'    => 'Report'
--}}
<script src="{{ asset('js/xlsx.full.min.js') }}"></script>
<script type="module">
    $(function(){
        $("#{{ $button }}").on('click', function(){
            let table = null;
            // TAB BASED EXPORT
            @if(!empty($tabBased) && $tabBased === true)
            
                let activeTab = document.querySelector('.tab-pane.active');
                if(!activeTab){
                    console.warn("No active tab found.");
                    return;
                }
                table = activeTab.querySelector("table");
                if(!table){
                    console.warn("No table inside active tab.");
                    return;
                }
            // NORMAL TABLE EXPORT
            @else
                table = document.getElementById("{{ $table ?? '' }}");
                if(!table){
                    console.warn("Table not found: {{ $table ?? '' }}");
                    return;
                }
            @endif
            let workbook = XLSX.utils.table_to_book(table, {
                sheet: "{{ $sheet ?? 'Report' }}"
            });
            let fileName = "{{ $filename ?? 'Report' }}";
            // If tab-based, append tab id to filename
            @if(!empty($tabBased) && $tabBased === true)
                let activeTabId = document.querySelector('.tab-pane.active')?.id;
                if(activeTabId){
                    fileName = fileName + "_" + activeTabId;
                }
            @endif

            XLSX.writeFile(workbook, fileName + ".xlsx");

        });

    });
</script>