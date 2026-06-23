{{-- Complaint details --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Complaint Details&nbsp;#{{ $complaint->code }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Consumer + Complaint heads --}}
            <x-consumer.complaint-details :complaint="$complaint" class="bg-info-subtle"/>
            {{-- Status History --}}
            <div>
                <x-consumer.complaint-statushistroy :complaint="$complaint" class="bg-info-subtle"/>
            </div>
            {{-- Feedback --}}
            @if ($complaint->feedback->count()>0)
                <h4 class="text-info text-decoration-underline">Feedback Details:</h4>
                @foreach ($complaint->feedback as $feedback)
                    <div class="mx-3 p-3 border">
                        <div>
                            <span class="fw-semibold"><i class="bi bi-person-heart"></i>&nbsp;{{ $feedback->collectable?->name }}</span>&nbsp;
                            <x-complaint.rating :rating="$feedback->rating"/>
                        </div>
                        <figure class="ms-3">
                            <blockquote class="blockquote">
                                <p>{{ $feedback->notes }}</p>
                            </blockquote>
                            <figcaption class="blockquote-footer">
                                {{ $feedback->created_at?->format('d-m-Y') }} <cite title="Source Title">EMP</cite>
                            </figcaption>
                        </figure>
                    </div>   
                @endforeach
            @endif

            {{-- Comments --}}
            <div class="m-3 border" id="comments">
                @include('complaints.comments')
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>

