@extends('layouts.app')

@section('content')

@if (Auth::check())
    <div="container"> Hello, {{ Auth::user()->username }}!</div>
    <div class="profile-picture" style="position: absolute; top: 10px; right: 10px;">
        <a href="{{ route('users.show', ['id' => Auth::user()->id]) }}">
            <img src="{{ Auth::user()->profilePicture() }}" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;">
        </a>
    </div>
    <div>
        @foreach ($user->posts() as $post)
            <div class="post-item">
                <img src="{{ $post->post_picture_id }}" alt="Post Picture">
                <p>{{ $post->description }}</p>
                <p>{{ $post->created_at }}</p>
            </div>
        @endforeach
    </div>
@else
    <div="container"> Hello, Stranger!</div>
@endif
@endsection