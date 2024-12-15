@section('head')
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

<div class="post-item-home">
    <div class="img-header">
        <div class="user">
            <div class="profile-picture2">
                <img src="{{ asset($post->user->getProfilePicture()) }}" alt="Profile Picture">
            </div>
            <a href="{{ route('users.show', $post->user->id) }}"> {{ $post->user->username }} </a>
        </div>

        <div class="icons">
            <span class="like-button"
                data-post-id="{{ $post->id }}"
                data-liked="{{ Auth::user()->likesPost($post) ? 'true' : 'false' }}">
                @if (Auth::user()->likesPost($post))
                    <i class="fas fa-heart"></i> <span class="like-count">{{ $post->likes()->count() }}</span>
                @else
                    <i class="far fa-heart"></i> <span class="like-count">{{ $post->likes()->count() }}</span>
                @endif
            </span>
            <a href="{{ route('posts.show', $post->id) }}">
                <span class="fas fa-comment"> {{ $post->comments->count() }}</span>
            </a>
            <div class="tagged-users-container">
                <i class="fas fa-tags show-tagged-users-icon" title="Tagged Users"> {{$post->tags->count()}}  </i>
                <div class="tagged-users-list hidden">
                    @foreach ($post->tags as $tag)
                        <div class="tagged-user">
                            <img src="{{ $tag->user ? $tag->user->getProfilePicture() : '' }}" alt="Tagged User Picture" class="tagged-user-pic">
                            @if ($tag->user)
                                <a href="{{ route('users.show', $tag->user->id) }}"> {{ $tag->user->firstname }} (@ {{ $tag->user->username }}) </a>
                            @else
                                <span>Unknown User</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @if ($post->id)
        <a href="{{ route('posts.show', $post->id) }}">
            <img src="{{ asset($post->getPostPicture()) }}" alt="Post Image">
        </a>
    @endif
</div>
