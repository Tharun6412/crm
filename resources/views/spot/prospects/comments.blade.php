{{-- Comments for Prospect --}}
<div class="bd-callout bd-callout-primary bg-transparent card mt-0 border-primary mb-3">
    <h4>Comments</h4>
    @if (!empty($prospect_data['comments']))
        <ul class="list-group border-top-0">
            <? foreach ($prospect_data['comments'] as $comment) { ?>
                <li class="list-group-item">
                    <div>
                        <?
                        if($this->spotaccess->isAdmin() OR $this->spotaccess->isClusterHead()) {
                            ?>
                            <a type="button" class="btn btn-link text-danger m-0 p-0" onclick="deleteComment(<? echo $comment['id']; ?>, <? echo $comment['lead_id']; ?>)">
                                <i class="bi bi-trash"></i>
                            </a>
                            <? 
                        }
                        ?>
                        <strong><? echo $comment['created_by']; ?>:</strong> <? echo $comment['comments']; ?></div>
                    <small class="float-end"><? echo displayDate($comment['created_at'], 1); ?></small>
                </li>
            <? } ?>
        </ul>
    @else
        <div class="alert alert-secondary mb-0">No comments!</div>
    @endif
    <div class="border rounded p-3 mt-3">
        <form action="" id="prsp-cmnt-form">
            <div class="form-floating mb-2">
                <textarea name="comments" class="form-control" placeholder="Leave a comment here" id="floatingComments"></textarea>
                {{-- <label for="floatingComments">Comment as {{ $this->session->userdata('emp')['firstname'] }} ({{ $this->session->userdata('emp')['user_name'] }})</label> --}}
                <label for="floatingComments">Comment as Guest</label>
                <div class="text-danger"><? echo validation_errors(' ', ' ') ?></div>
                <div class="form-text text-end fst-italic">Maximum length of comment is 600 characters </div>
            </div>
            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-chat"></i>&nbsp;Add Comment</button>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('#prsp-cmnt-form').submit(function(e){
            e.preventDefault();
            var params = $(this).serializeArray();
            $.post($(this).attr('action'), params, function(data) {
                $('#prsp-cmnts').html(data);
            });
        })
    });
</script>