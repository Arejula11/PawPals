@extends('layouts.app')
@section('head')
    <!-- Include only the CSS for this view -->
    <link href="{{ asset('css/viewProfile.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="user-profile">
    <!-- Header -->
    <header class="profile-header">

        <img src="{{ $user->getProfilePicture()  }}" alt="Profile Picture" class="profile-image">
        <div class="user-info">
            <h1 class="username">{{ $user->username }}</h1>
            <h3>{{ $user->firstname }} {{ $user->surname }}</h2>
            <h3>{{ $user->type}}</h2>
            <div class="follower-stats">
                <span><strong> {{ $user->followers()->count() }}</strong> Followers</span>
                <span><strong>{{ $user->follows()->count() }}</strong> Following</span>
            </div>
            <span><strong>About me:</strong></span>
            <p class="profile-description">{{ $user->bio_description }}</p>

        </div>
    </header>

    <!-- Body -->
    <div class="profile-body">
        <!-- Left column (user's posts) -->
        <div class="posts">
            <h2>Posts</h2>
            @foreach ($user->posts as $post)
                <div class="post-item">
                    <h3>{{ $post->title }}</h3>
                    <p>{{ $post->content }}</p>
                    <span class="post-date">Published on {{ $post->created_at->format('d M, Y') }}</span>
                </div>
            @endforeach
        </div>

        <!-- Right column (groups) -->
        <div class="groups">
            <h2>Groups</h2>
            @if ($user->groups->isEmpty())
                <p>Does not belong to any group.</p>
            @else
                <ul>
                    @foreach ($user->groups as $group)
                        <li><a href="{{ route('groups.show', $group->id) }}">{{ $group->name }}</a></li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection