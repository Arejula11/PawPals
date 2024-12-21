@section('head')
    <link href="{{ asset('css/viewPost.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

<div class="icons">
    @auth

    <span class="like-button" 
        data-post-id="{{ $post->id }}" 
        data-liked="{{ Auth::user()->likesPost($post) ? 'true' : 'false' }}">
        @if (Auth::user()->likesPost($post))
            <i class="fas fa-heart"></i> <span class="like-count">{{ $post->likes()->count() }}</span>
        @else
            <i class="far fa-heart"></i> <span class="like-count">{{ $post->likes()->count() }}</span>
        @endif
    </span>

    @endauth
    @guest
        <form action=" {{ route('register') }} " method="POST">
            @csrf
            <span class="far fa-heart" onclick="submitLike(event)"> {{ $post->likes()->count() }} </span> 
            <label for="like"></label>
        </form>
    @endguest
    
    <span class="fas fa-comment" onclick="focusCommentBox()"> {{ $post->comments->count() }} </span>
    
    <section class="tagged-users-container">
        <i class="fas fa-tags show-tagged-users-icon" title="Tagged Users"> {{$post->tags->count()}}  </i>
        <section class="tagged-users-list hidden">
            @foreach ($post->tags as $tag)
                <section class="tagged-user">
                    <img src="{{ $tag->user ? $tag->user->getProfilePicture() : '' }}" alt="Tagged User Picture" class="tagged-user-pic">
                    @if ($tag->user)
                        <a href="{{ route('users.show', $tag->user->id) }}"> {{ $tag->user->firstname }} (@ {{ $tag->user->username }}) </a>
                    @else
                        <span>Unknown User</span>
                    @endif
                </section>
            @endforeach
        </section>
    </section>
</div>

<script>

    document.addEventListener('DOMContentLoaded', function() {
        const toggleIcon = document.querySelector('.show-tagged-users-icon');
        const taggedUsersList = document.querySelector('.tagged-users-list');

        toggleIcon.addEventListener('click', function() {
            taggedUsersList.classList.toggle('hidden');
            taggedUsersList.classList.toggle('visible');
        });

        document.addEventListener('click', function(event) {
            if (!toggleIcon.contains(event.target) && !taggedUsersList.contains(event.target)) {
                taggedUsersList.classList.add('hidden');
                taggedUsersList.classList.remove('visible');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.like-button').forEach(button => {
            button.addEventListener('click', function () {
                const postId = button.getAttribute('data-post-id');
                const isLiked = button.getAttribute('data-liked') === 'true';
                const url = isLiked
                    ? `/posts/${postId}/likes/destroy`
                    : `/posts/${postId}/likes/store`;
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
                            console.error('Error liking/unliking the post:', data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    });

</script>