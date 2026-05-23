{{-- Status Report --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">
                Converted Consumers -
                {{ $request_data['ga_name'] }}&nbsp;>&nbsp;({{ $request_data['date_from'] }}&nbsp;to&nbsp;{{ $request_data['date_to'] }})&nbsp;{{ $segment ? "> ".$segment : '' }}
            </h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
           <div id="reports-list">
                @include('reports.consumer.conversions.prepaid-list-body')
           </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
