<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{ url('consumers/prepaid/hesSubmit/'.$consumer->id) }}" name="hes-submit-form" id="hes-submit-form" method="post">
                @csrf
                @method('PUT')
                <x-consumer.basic-details :consumer="$consumer" class="bg-warning-subtle" />
                <div>
                    <label for="meter_no">Meter No&nbsp;:&nbsp;</label>
                    <div>{{ $consumer->activeMeter->meter_no }}</div>
                </div>
                <button type="submit" class="btn btn-success btn-sm">Confirm</button>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>