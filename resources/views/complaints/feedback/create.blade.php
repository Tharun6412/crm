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
            {{-- Complaint details --}}
            <div class="fs-5 px-3 fw-bold">Complaint Details:</div>
            <div class="row g-2">
                <div class="col-sm-2 text-end fw-semibold">Type : </div>
                <div class="col-sm-4">{{ $complaint->type?->name }}</div>
                <div class="col-sm-2 text-end fw-semibold">Media : </div>
                <div class="col-sm-4">{{ $complaint->media?->name }}</div>
                <div class="col-sm-2 text-end fw-semibold">Category : </div>
                <div class="col-sm-4">{{ $complaint->category?->parent->name }}</div>
                <div class="col-sm-2 text-end fw-semibold">Department : </div>
                <div class="col-sm-4">{{ $complaint->category?->department->name }}</div>
                <div class="col-sm-2 text-end fw-semibold">Tag : </div>
                <div class="col-sm-4">{{ $complaint->category->tag?->name }}</div>
                <div class="col-sm-2 text-end fw-semibold">Sub Category : </div>
                <div class="col-sm-4">{{ $complaint->category?->name }}</div>
                <div class="col-sm-2 text-end fw-semibold">Resolution : </div>
                <div class="col-sm-4">
                    {{ $complaint->category?->resolution }}&nbsp;{{ ($complaint->category?->resolution_type == 1) ? "Days" : "Hours" }}
                </div>
                {{-- <div class="col-sm-6"></div> --}}
                <div class="col-sm-2 text-end fw-semibold">Rating : </div>
                <div class="col-sm-4">
                    @if ($complaint->feedback)
                        <x-complaint.rating :rating="$complaint?->feedback->rating"/>
                    @endif
                </div>
                <div class="col-sm-2 text-end fw-semibold">Description : </div>
                <div class="col-sm-10">{{ $complaint->description }}</div>
                <div class="col-sm-2 text-end fw-semibold"><i class="bi bi-paperclip"></i>Documents : </div>
                <div class="col-sm-10">
                    @if ($complaint->complaintDocuments->count() > 0)
                        @foreach ($complaint->complaintDocuments as $document)
                            <a href="{{ url('dc/documents/' . $document->file_id) }}" title="{{ $document->file->file_name }}" target="_blank"><i class="bi bi-file-earmark-pdf fs-3"></i></a>        
                        @endforeach
                    @endif
                </div>
            </div>
            <hr/>
            {{-- Feedback Status History --}}
            <div>
                <h4>Status History</h4>
                <table class="table table-bordered table-info mb-0">
                    <thead class="table-info">
                        <tr>
                            <th width="1%" nowrap>S.No</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th>Added By</th>
                            <th>Added Date</th>
                        </tr>
                    </thead>
                    <tbody>
                       <tbody>
                        @forelse ($complaint->statushistory->where('status_id',\App\Enums\ComplaintStatus::CLOSE) as $status_val)
                           <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><x-complaint.status :status="$status_val->status"/></td>
                                <td>{{ $status_val->notes }}</td>
                                <td>{{ $status_val->createdBy?->first_name }}&nbsp;{{ $status_val->createdBy?->last_name }}</td>
                                <td>{{ $status_val->created_at?->format('d-m-Y') }}</td>
                            </tr> 
                        @empty
                            <tr>
                                <td>the is no complaint colse</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
