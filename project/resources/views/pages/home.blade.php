@extends('layouts.app')

@section('head')

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
    
    <form action="{{ route('requests.show') }}" method="GET" style="position: absolute; top: 15px; right: 70px;">
        <button type="submit" style="background-color:white; border:none" >
            <img src="/images/follow.png" alt="Follow Requests" class="requests-follow">
        </button>
    </form>

    <div class="profile-picture" style="position: absolute; top: 10px; right: 10px;">
        <a href="{{ route('users.show', ['id' => Auth::user()->id]) }}">
            <img src="{{ Auth::user()->getProfilePicture() }}" alt="Profile Picture" class="profile-img">
        </a>
    </div>


@else
    <div="container"> Hello, Stranger!</div>
@endif
@endsection