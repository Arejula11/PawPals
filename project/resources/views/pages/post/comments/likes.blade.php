@section('head')
    <link href="{{ asset('css/viewPost.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

<div class="icons">
    <span class="comment-like-button" 
        data-post-id="{{ $post->id }}" 
        data-comment-id="{{ $comment->id }}" 
        data-liked="{{ Auth::user()->likesComment($comment) ? 'true' : 'false' }}">
        @if (Auth::user()->likesComment($comment))
            <i class="fas fa-heart"></i> <span class="like-count">{{ $comment->likes()->count() }}</span>
        @else
            <i class="far fa-heart"></i> <span class="like-count">{{ $comment->likes()->count() }}</span>
        @endif
    </span>
    <span
        class="fa fa-comments" 
        onclick="setReplyToComment('{{ $comment->id }}', '{{ $comment->user->username }}')"
        title="Reply to this comment">
        {{ $comment->childComments()->count() }}
    </span>
</div>

<script>

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.comment-like-button').forEach(button => {
        button.addEventListener('click', function () {
            if (button.disabled) return;
            button.disabled = true;

            const postId = button.getAttribute('data-post-id');
            const commentId = button.getAttribute('data-comment-id');
            const isLiked = button.getAttribute('data-liked') === 'true';
            const url = isLiked
                ? `/posts/${postId}/comments/${commentId}/likes/destroy`
                : `/posts/${postId}/comments/${commentId}/likes/store`;
            const method = isLiked ? 'DELETE' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        button.setAttribute('data-liked', !isLiked);
                        button.querySelector('i').classList.toggle('fas', !isLiked);
                        button.querySelector('i').classList.toggle('far', isLiked);
                        button.querySelector('.like-count').textContent = data.likeCount;
                    } else {
                        console.error('Error liking/unliking the comment:', data.message);
                    }
                })
                .catch(error => console.error('Error:', error))
                .finally(() => {
                    button.disabled = false; 
                });
        });
    });
});


</script>