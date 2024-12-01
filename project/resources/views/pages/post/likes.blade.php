@section('head')
    <link href="{{ asset('css/viewPost.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

<div class="icons">
    <form action=" {{ route('posts.likes.store', $post->id) }} " method="POST">
        @csrf
        <span class="likes">
            <button type="submit" class="fa fa-heart"></button> {{ $post->likes()->count() }}
        </span>    
    </form>
        
   
    <span class="comments">
        <i class="fa fa-comments"></i> {{ $post->comments->count() }}
    </span>
</div>