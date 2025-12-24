{{-- Comments for Prospect --}}
<a class="visually-hidden" href="{{ url('calls/'.$complaint->id.'?reload=true') }}" data-custom-attr="value" id="reload-comments">Hidden Link</a>
<div class="bordered">
    <h4>Comments</h4>
    @if ($complaint->comments->count() > 0)
        <ul class="list-group border-top-0">
            @foreach ($complaint->comments as $comment)
                <li class="list-group-item">
                    <div>
                        <a type="button" class="btn btn-link text-danger m-0 p-0" onclick="deleteComment('{{ $comment->id }}')">
                            <i class="bi bi-trash"></i>
                        </a>
                        <strong>{{ $comment->commentable->name }}:</strong> {{ $comment->comments }}
                    </div>
                    <small class="float-end">{{ $comment->created_at?->format('d-m-Y H:i:s') }}</small>
                </li>
            @endforeach
        </ul>
    @else
        <div class="alert alert-secondary mb-0">No comments!</div>
    @endif
    <div class="border rounded p-3 mt-3">
        <form action="{{ url('calls/comments/'.$complaint->id) }}" id="cmp-comment-form" method="POST">
            @csrf
            <div class="form-floating mb-2">
                <textarea name="comments" class="form-control" placeholder="Leave a comment here" id="comments"></textarea>
                <label for="floatingComments">Comment as {{ Auth::user()->first_name }}&nbsp;{{ Auth::user()->last_name }}</label>
                <div class="text-danger" id="comments-error"></div>
                <div class="form-text text-end fst-italic">Maximum length of comment is 600 characters </div>
            </div>
            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-chat"></i>&nbsp;Add Comment</button>
        </form>
    </div>
</div>
@include('scripts.ajax-file-submit', ['form' => 'cmp-comment', 'callback' => 'reloadComments()'])
<script type="text/javascript">
    //To reload Comment Section 
    function reloadComments()
    {
        $.get($('#reload-comments').attr('href'), function(data) {
            $('#comments').html(data);
        });
    }

    // To Delete Comment By ID
    function deleteComment(id)
    {
        $.post("{{ url('calls/deleteComment') }}/"+id, {_token:"{{ csrf_token() }}"}, function(data) {
            reloadComments();
        });
    }
</script>
