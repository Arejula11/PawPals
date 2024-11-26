@extends('layouts.app')

@section('head')
    <!-- Include only the CSS for this view -->
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endsection
@section('content')

@if (Auth::check())
    <div="container"> Hello, {{ Auth::user()->username }}!
    <div class="post-gallery-home">
        <h1>Posts</h1>
        @foreach ($postImages as $image)
            <div class="post-item-home">
                <img src="{{ asset($image) }}" alt="Post Image">
            </div>
        @endforeach
    </div>
    </div>
    

    

    <div class="profile-picture" style="position: absolute; top: 10px; right: 10px;">
        <a href="{{ route('users.show', ['id' => Auth::user()->id]) }}">
            <img src="{{ Auth::user()->getProfilePicture() }}" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;">
        </a>
    </div>


@else
    <div="container"> Hello, Stranger!</div>
@endif
@endsection