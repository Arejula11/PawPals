@extends('layouts.app')
@section('head')
    <link href="{{ asset('css/viewPost.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('content')
<div class="post-view">
    
    <section class="image">
        <img src="{{ asset($post->getPostPicture()) }}" alt="Post Image">
    </section>

    <section class="image-info">
        
        <section class="user">
            <section class="profile-picture">
                <img src="{{ $post->user->getProfilePicture()  }}" alt="Profile Picture">
            </section>
            <a href="{{ route('users.show', $post->user->id) }}" > {{ $post->user->username }} </a>
        </section>

        <section class="content">
            <span class="description"> {{ $post->description }} </span>
        </section>

        {{-- New: Tagged Users Section --}}

        <section class="d-i">
            @include('pages.post.likes')
            <p class="date">Created on: {{ $post->creation_date }}</p>
        </section>
                
        <section class="comments-section">
            @foreach ($post->comments->where('previous_comment_id', null) as $comment)
                <div class="comment" id="comment-{{ $comment->id }}">
                    <section class="user2">
                        <section class="profile-picture2">
                            <img src="{{ $comment->user->getProfilePicture()  }}" alt="Profile Picture">
                        </section>
                        <a href="{{ route('users.show', $comment->user->id) }}" > {{ $comment->user->username }} </a>
                    </section>
                    <p> {{ $comment->content }} </p>
                    <section class="d-i">
                        @include('pages.post.comments.likes')
                        <p class="date">Created on: {{ $comment->date }}</p>
                    </section>
                    @if ($comment->childComments)
                        <div class="replies">
                        @foreach ($comment->childComments as $reply)
                            <div class="comment reply" id="comment-{{ $reply->id }}">
                                
                                <section class="user2">
                                    <section class="profile-picture2">
                                        <img src="{{ $reply->user->getProfilePicture()  }}" alt="Profile Picture">
                                    </section>
                                    <a href="{{ route('users.show', $reply->user->id) }}" > {{ $reply->user->username }} </a>
                                </section>
                                
                                <p>{{ $reply->content }}</p>
                                
                                <section class="d-i">
                                    @auth
                                    @if (Auth::user()->likesComment($reply))
                                        <form action="{{ route('comments.likes.destroy', ['post' => $post->id, 'comment' => $reply->id]) }}" method="POST">
                                            @csrf 
                                            <span class="fas fa-heart" onclick="submitLike(event)"> {{ $reply->likes()->count() }} </span>
                                        </form>
                                    @else
                                        <form action="{{ route('comments.likes.store', ['post' => $post->id, 'comment' => $reply->id]) }}" method="POST">
                                            @csrf
                                            <span class="far fa-heart" onclick="submitLike(event)"> {{ $reply->likes()->count() }} </span>    
                                        </form>
                                    @endif 

                                    @endauth
                                    @guest
                                        <form action=" {{ route('register') }} " method="POST">
                                            @csrf
                                            <span class="far fa-heart" onclick="submitLike(event)"> {{ $reply->likes()->count() }} </span> 
                                        </form>
                                    @endguest
                                    <p class="date">Created on: {{ $reply->date }}</p>
                                </section>
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            @endforeach
        </section>

        <section class="comment-input">
            <form action="{{ route('posts.comments.store', ['id' => $post->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="reply-to" name="previous_comment_id" value="">
                <textarea id="comment-box" name="content" placeholder="Add new comment..." rows="1" required></textarea>
                <button type="submit"> Post </button>    
            </form>
        </section>

    </section>
</div>
@endsection



