@extends('layouts.app')

@section('head')

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
        <button type="button" style="background-color:white; border:none">
            <img src="/images/follow.png" alt="Follow Requests" class="requests-follow">
            @if ($pendingRequestsCount > 0)
                <span class="badge" style="text-align:center">{{ $pendingRequestsCount }}</span>
            @endif
        </button>
    </form>


    <div class="follow-container">
        <h1>Requests to follow</h1>
        <div class="scrollable-content">
            @foreach ($pendingRequests as $request)
                <div class="request-item">
                    <a href="{{ route('users.show', $request->follower->id) }}" class="user-link">
                        <img class="profile-picture-request" src="profile/{{ $request->follower->profile_picture }}" alt="{{ $request->follower->username }}'s profile picture">
                    </a>
                    <p class="request-phrase">{{ $request->follower->username }}</p>
                    <form action="{{ route('follow.accept', ['user1_id' => $request->follower->id, 'user2_id' => $request->user2_id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="request-accept">
                            <img src="/images/accept.png" alt="Follow accepted">
                        </button>
                    </form>
                    <form action="{{ route('follow.reject', ['user1_id' => $request->follower->id, 'user2_id' => $request->user2_id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="request-decline">
                            <img src="/images/decline.png" alt="Follow declined">
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>


    <div class="profile-picture" style="position: absolute; top: 10px; right: 10px;">
        <a href="{{ route('users.show', ['id' => Auth::user()->id]) }}">
            <img src="{{ Auth::user()->getProfilePicture() }}" alt="Profile Picture" class="profile-img">
        </a>
    </div>


@else
    <div="container"> Hello, Stranger!</div>
@endif
@endsection
@section('scripts')
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const followButton = document.querySelector(".requests-follow");
        const followContainer = document.querySelector(".follow-container");

        followButton.addEventListener("click", function (event) {
        event.preventDefault(); // Prevent form submission
        followContainer.classList.toggle("active");
        });
    });
    </script>
@endsection