@section('head')
    <link href="{{ asset('css/viewPost.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

<div class="icons">
    @auth

    @if (Auth::user()->likesPost($post))
        <form action=" {{ route('posts.likes.destroy', $post->id) }} " method="POST">
            @csrf 
           <span class="fas fa-heart" onclick="submitLike(event)"> {{ $post->likes()->count() }} </span>
        </form>
    @else
        <form action=" {{ route('posts.likes.store', $post->id) }} " method="POST">
            @csrf
            <span class="far fa-heart" onclick="submitLike(event)"> {{ $post->likes()->count() }} </span>    
        </form>
    @endif 

    @endauth
    @guest
        <form action=" {{ route('register') }} " method="POST">
            @csrf
            <span class="far fa-heart" onclick="submitLike(event)"> {{ $post->likes()->count() }} </span> 
        </form>
    @endguest
    
    <span class="fas fa-comment" onclick="focusCommentBox()"> {{ $post->comments->count() }} </span>
    
    <section class="tagged-users-container">
        {{-- Font Awesome Tag Icon --}}
        <i class="fas fa-tags show-tagged-users-icon" title="Tagged Users"> {{$post->tags->count()}}  </i>
        <section class="tagged-users-list hidden">
            @foreach ($post->tags as $tag) {{-- Limit to 5 users --}}
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
</script>