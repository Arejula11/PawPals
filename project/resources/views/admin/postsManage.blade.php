@extends('layouts.admin')
@section('head')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="header">
        <a href="{{route('admin.home')}}" class="name">PetPawls</a>
    </div>

    <h1 style="font-size: 32px; margin-bottom: 10px; margin-top: -40px; text-align: center; color: #333; font-weight: semi-bold;">Manage posts</h1>
    <div class="posts">
        <div class="post-header">Posts List</div>
        @foreach($posts as $post)
            <div class="post">
                <a href="{{ route('admin.posts.show', $post->id) }}">
                    <div class="post-details">
                        <img class="post-img" src="{{ asset($post->getPostPicture()) }}" alt="Post Picture">
                        <p class="post-meta">Title: {{ $post->description }}</p>
                        <p class="post-meta">Date: {{ $post->creation_date }}</p>
                        <p class="post-meta">User ID: {{ $post->user_id }}</p>
                    </div>
                </a>
            </div>
        @endforeach
        </div>
    </div>
    <div class="pagination">
        {{ $posts->links() }}
    </div>
@endsection