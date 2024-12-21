@extends('layouts.admin')
@section('head')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@php
    use App\Models\User;
    use App\Models\Comment;
    $previous = $comment->previous_comment_id;
    if ($previous == null) {
        $previous = "None";
    } else {
        $previous = Comment::find($previous)->content;
    }

@endphp

@section('content')
    <div class="header">
        <a href="{{ route('admin.home') }}" class="name">PetPawls</a>
    </div>

    <h1 class="page-title">Manage comment</h1>
    <div class="comments-container">
        <div class="comments">
            <h3>Comments</h3>

                <div class="comment">
                    <form action="{{ route('admin.comments.edit', $comment->id) }}" method="POST" class="edit-form">
                        @csrf
                        @method('PUT')
                        <label for="edit"></label>
                        <input name="content" value="{{ $comment->content }}">
                        <button type="submit" class="btn edit-btn">Edit</button>
                    </form>
                        <div class="comment-details">
                            <p class="comment-meta">User: {{ User::find($comment->user_id)->username }}</p>
                            <p class="comment-meta">Date: {{ $comment->date }}</p>
                            <p class="comment-meta">Previous comment: {{ $previous }}</p>
                        </div>
                </div>
                <div class="post-buttons">
                    
                    <form action="{{ route('admin.comments.delete', $comment->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <label for="submit"></label>
                        <button type="submit" class="btn delete-btn">Delete</button>
                    </form>
                </div>
        </div>
    </div>
@endsection
