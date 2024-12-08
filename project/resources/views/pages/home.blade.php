@extends('layouts.app')

@section('head')
    <!-- Include only the CSS for this view -->
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endsection
@section('content')

@if (Auth::check())
    <div="container"> Hello, {{ Auth::user()->username }}!
        <div class="post-gallery-home">
            <section class="timeline"> 
                <h1>Public</h1>
                <h1>Following</h1>        
            </section>
            @foreach ($posts as $post)
                <div class="post-item-home">
                    <section class="user">
                        <section class="profile-picture2">
                            <img src="{{ asset($post->user->getProfilePicture())  }}" alt="Profile Picture">
                        </section>
                        <a href="{{ route('users.show', $post->user->id) }}" > {{ $post->user->username }} </a>
                    </section>
                    <img src="{{ asset($post->getPostPicture()) }}" alt="Post Image">
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