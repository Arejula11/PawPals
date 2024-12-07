@extends('layouts.app')
@section('head')

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
            @if ($isOwnProfile)
                <a href="{{ route('users.update', $user->id) }}" class="btn btn-primary">Edit Profile</a>
            @else
                @if ($followStatus === 'pending')
                    <button class="btn btn-secondary" disabled>Request Sent</button>
                @elseif ($followStatus === 'accepted')
                    <button class="btn btn-success" disabled>Following</button>
                @else
                    <form method="POST" action="{{ route('follow.send') }}">
                        @csrf
                        <input type="hidden" name="user1_id" value="{{ Auth::id() }}">
                        <input type="hidden" name="user2_id" value="{{ $user->id }}">

                        @if ($user->is_public)
                            <button type="submit" class="btn btn-primary">Follow</button>
                        @else
                            @if ($followStatus === 'pending')
                                <button class="btn btn-secondary" disabled>Request Sent</button>
                            @elseif ($followStatus === 'accepted')
                                <button class="btn btn-success" disabled>Following</button>
                            @else
                                <button type="submit" class="btn btn-primary">Send Follow Request</button>
                            @endif
                        @endif
                    </form>
                @endif
            @endif
        </div>
    </header>

    <!-- Body -->
    <div class="profile-body">
        <!-- Left column (user's posts) -->
        <div class="posts">
            <h2>Posts</h2>
            <div class="post-gallery-show">
                @foreach ($postImages as $image)
                    <div class="post-item-show">
                        <img src="{{ asset($image) }}" alt="Post Image">
                    </div>
                @endforeach
            </div>
            
        </div>
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