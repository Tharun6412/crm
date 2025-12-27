{{-- Complaint feedback --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Complaint Feedback&nbsp;#{{ $complaint->code }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Consumer + Complaint Details --}}
            <div>
                <x-consumer.complaint-details :complaint="$complaint" class="bg-info-subtle"/>
            </div>
            <div id="feedback-success" class="mt-3">
                <h4>Give Feedback</h4>
                <form id="feedback-form" action="{{ url('calls/feedback/'.$complaint->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-2 text-end">Rating<span class="text-danger">*</span>&nbsp;:</label>
                        <div class="col-sm-8">
                            <div class="rating">
                                <input type="radio" name="rating" id="star5" value="5">
                                <label for="star5"><i class="bi bi-star-fill"></i></label>
                                <input type="radio" name="rating" id="star4" value="4">
                                <label for="star4"><i class="bi bi-star-fill"></i></label>
                                <input type="radio" name="rating" id="star3" value="3">
                                <label for="star3"><i class="bi bi-star-fill"></i></label>
                                <input type="radio" name="rating" id="star2" value="2">
                                <label for="star2"><i class="bi bi-star-fill"></i></label>
                                <input type="radio" name="rating" id="star1" value="1">
                                <label for="star1"><i class="bi bi-star-fill"></i></label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-2 text-end">Feedback<span class="text-danger">*</span>&nbsp;:</label>
                        <div class="col-sm-6">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="mb-2" id="feedback-error"></div>
                    <div class="row">
                        <div class="offset-sm-2 col-sm-8">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-quote" aria-hidden="true">&nbsp;</i>Submit Feedback
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'feedback'])
<style>
    .rating {
        direction: rtl;
        display: inline-flex;
        font-size: 1.8rem;
    }
    .rating input {
        display: none;
    }
    .rating label {
        color: #ddd;
        cursor: pointer;
        padding-left: 5px;
        transition: color 0.2s;
    }
    .rating input:checked ~ label,
    .rating label:hover,
    .rating label:hover ~ label {
        color: #ffc107; /* Bootstrap warning color */
    }
</style>
