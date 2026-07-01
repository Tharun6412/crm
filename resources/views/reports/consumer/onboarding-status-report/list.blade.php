{{-- Status Report --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">
                Consumer Status - 
                <span class='text-primary'>{{ $request_data['ga_name'] }}</span>&nbsp;>&nbsp;<span class='text-success'>({{ $request_data['date_from'] }}&nbsp;-&nbsp;{{ $request_data['date_to'] }})</span>&nbsp;{{ $connect_type ? "> ".$connect_type : '' }}&nbsp;{{ $segment ? "> ".$segment : '' }}
            </h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
           <div id="reports-list">
                @include('reports.consumer.onboarding-status-report.list-body')
           </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
