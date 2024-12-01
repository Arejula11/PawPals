@extends('layouts.app')
@section('head')
    <link href="{{ asset('css/viewPost.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            <!-- <h6> <a href="{{ route('users.show', $post->user->id) }}" > {{ $post->user->username }} </a> </h6> -->
            <a href="{{ route('users.show', $post->user->id) }}" > {{ $post->user->username }} </a>
        </section>

        <section class="content">
            <p class="description"> {{ $post->description }} </p>
            <section class="d-i">
                @include('pages.post.likes')
                <p class="date">Created on: {{ $post->creation_date }}</p>
            </section>
        </section>
                
        <section class="comments-section">
            @foreach ($post->comments as $comment)
                <div class="comment">
                    <span class="user"><strong>{{ $post->user->username }}:</strong></span>
                    <p> {{ $comment->content }} </p>
                    <section class="d-i">
                        <div class="icons">
                            <span class="likes">
                                <i class="fa fa-heart"></i> 100 Likes
                            </span>
                            <span class="comments">
                                <i class="fa fa-comments"></i> 50 Comments
                            </span>
                        </div>
                        <p class="date">Created on: {{ $comment->date }}</p>
                    </section>
                </div>
            @endforeach
        </section>

        <section class="comment-input">
            <form action="{{ route('posts.comments.store', ['id' => $post->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <textarea name="content" placeholder="Add new comment..." rows="1" required></textarea>
                <button type="submit"> Post </button>    
            </form>
        </section>

    </section>
</div>
@endsection



