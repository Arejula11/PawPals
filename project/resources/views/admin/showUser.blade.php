@extends('layouts.admin')
@section('head')
    <!-- Include only the CSS for this view -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="header">
        <a href="{{route('admin.home')}}" class="name">PetPawls</a>
    </div>

    <h1 style="font-size: 32px; margin-bottom: 10px; margin-top: -40px; text-align: center; color: #333; font-weight: semi-bold;">Manage user</h1>
    <div class="user-profile">
        <header class="profile-header" style="border: 1px solid #ccc; padding: 20px; border-radius: 10px">
            
            <img src="{{ $user->getProfilePicture()  }}" alt="Profile Picture" class="profile-image">
            <div class="user-info">
                <h2 class="username">{{ $user->username }}</h1>
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
        <div class="action-bento">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="action-item edit">Edit</a>
            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="action-item delete" style="display:inline;">
                @csrf
                @method('PUT')
                <button type="submit" style="background:none; border:none; color:#007bff; text-decoration:underline; cursor:pointer;">Delete</button>
            </form>
            @if(!$user->isBanned())
                <a href="{{ route('admin.users.ban', $user->id) }}" class="action-item ban">Ban</a>
            @endif
        </div>

   
    </div>
</div>
@endsection