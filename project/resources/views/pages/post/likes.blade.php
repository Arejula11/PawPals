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

</div>