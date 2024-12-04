@section('head')
    <link href="{{ asset('css/viewPost.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

<div class="icons">
    @auth
    @if (Auth::user()->likesComment($comment))
        <form action="{{ route('comments.likes.destroy', ['post' => $post->id, 'comment' => $comment->id]) }}" method="POST">
            @csrf 
            <span class="fas fa-heart" onclick="submitLike(event)"> {{ $comment->likes()->count() }} </span>
        </form>
    @else
        <form action="{{ route('comments.likes.store', ['post' => $post->id, 'comment' => $comment->id]) }}" method="POST">
            @csrf
            <span class="far fa-heart" onclick="submitLike(event)"> {{ $comment->likes()->count() }} </span>    
        </form>
    @endif 

    @endauth
    @guest
        <form action=" {{ route('register') }} " method="POST">
            @csrf
            <span class="far fa-heart" onclick="submitLike(event)"> {{ $comment->likes()->count() }} </span> 
        </form>
    @endguest
    <span
        class="fa fa-comments" 
        onclick="setReplyToComment('{{ $comment->id }}', '{{ $comment->user->username }}')"
        title="Reply to this comment">
        {{ $comment->childComments()->count() }}
    </span>

</div>