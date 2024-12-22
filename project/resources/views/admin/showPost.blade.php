@extends('layouts.admin')
@section('head')
    <title>Show Post</title>
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@php
    use App\Models\Post;
    $id = request()->route('id');
    $post = Post::find($id);
    use App\Models\User;
    $user = User::find($post->user_id);
    use App\Models\Comment;
    $comments = Comment::where('post_id', $post->id)->get();
@endphp

@section('content')
    <div class="header">
        <a href="{{ route('admin.home') }}" class="name">PetPawls</a>
    </div>

    <h1 class="page-title">Manage post</h1>

    <div class="post-show-container">
        <!-- Post Details Section -->
        <div class="post-content">
            <div class="post-show-details">
                <img class="post-show-img" src="{{ asset($post->getPostPicture()) }}" alt="Post Picture">
                <div class="post-show-meta-container">
                    <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" class="edit-form">
                        @csrf
                        @method('PUT')
                        <label for="description"></label>
                        <input name="description" value="{{ $post->description }}">
                        <button type="submit" class="btn edit-btn">Edit</button>
                    </form>
                    <p class="post-meta">Title: {{ $post->description }}</p>
                    <p class="post-meta">Date: {{ $post->creation_date }}</p>
                    <p class="post-meta">User: {{ $user->username }}</p>
                </div>
                <div class="post-buttons">
                    
                    <form action="{{ route('admin.posts.delete', $post->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <label for="delete"></label>
                        <button type="submit" class="btn delete-btn">Delete</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="comments-container">
            <div class="comments">
                <h3>Comments</h3>
                @foreach($comments as $comment)
                    <div class="comment">
                        <a href="{{ route('admin.comments.show', $comment->id) }}">
                            <div class="comment-details">
                                <p class="comment-meta">User: {{ User::find($comment->user_id)->username }}</p>
                                <p class="comment-meta">Date: {{ $comment->date }}</p>
                                <p class="comment-meta">Content: {{ $comment->content }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
