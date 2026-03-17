{{-- complaint Comments --}}

<a class="visually-hidden" href="{{ url('calls/'.$complaint->id.'?reload=true') }}" data-custom-attr="value" id="reload-comments">Hidden Link</a>
<div>
    <h4 class="p-3 bg-light text-info"><i class="bi bi-chat-square-text"></i>&nbsp;Comments&nbsp;({{ $complaint->comments->count() }})</h4>
    @if ($complaint->comments->count() > 0)
        <ul class="list-group list-group-flush px-3">
            @foreach ($complaint->comments as $comment)
                <li class="list-group-item {{ ($loop->iteration % 2 == 0) ? 'text-end' : '' }} border-light">
                    <div>
                        @if (isSuperAdmin() OR isAdmin())
                            <a type="button" class="btn btn-link text-danger m-0 p-0" onclick="deleteComment('{{ $comment->id }}')">
                                <i class="bi bi-trash"></i>
                            </a>
                        @endif
                        <strong>{{ $comment->commentable->name }}:</strong>
                        <small class="fs-sm">{{ $comment->created_at?->format('d-m-Y H:i:s') }}</small>
                    </div>
                    {{ $comment->comments }}
                </li>
            @endforeach
        </ul>
    @else
        <div class="alert alert-secondary m-3">No comments!</div>
    @endif
    {{-- Enable only progressive complaints --}}
    @if (!in_array($complaint->status_id, [5, 6]))
        <div class="border rounded p-3 m-3">
            <form action="{{ url('calls/comments/'.$complaint->id) }}" id="cmp-comment-form" method="POST">
                @csrf
                <div class="form-floating mb-2">
                    <textarea name="comments" class="form-control" placeholder="Leave a comment here" id="comments"></textarea>
                    <label for="floatingComments">Comment as {{ Auth::user()->first_name }}&nbsp;{{ Auth::user()->last_name }}</label>
                    <div class="text-danger" id="comments-error"></div>
                    <div class="form-text text-end fst-italic">Maximum length of comment is 200 characters </div>
                </div>
                <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-chat"></i>&nbsp;Add Comment</button>
            </form>
        </div>
    @endif
</div>
@include('scripts.ajax-file-submit', ['form' => 'cmp-comment', 'callback' => 'reloadComments()'])
<script type="text/javascript">
    //To reload Comment Section 
    function reloadComments() {
        $.get($('#reload-comments').attr('href'), function(data) {
            $('#comments').html(data);
        });
    }

    // To Delete Comment By ID
    function deleteComment(id) {
        if(confirm('Are you sure to delete?')) {
            $.post("{{ url('calls/deleteComment') }}/"+id, {_token:"{{ csrf_token() }}"}, function(data) {
                reloadComments();
            });
        }
    }
</script>
