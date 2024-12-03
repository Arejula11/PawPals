@extends('layouts.admin')
@section('head')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="header">
        <h1 class="name">PetPawls</h1>
        <h1>Users</h1>
    </div>
    <div class="user-bento">
        @foreach($users as $user)
            <a href="{{ url('admin/users/' . $user->id) }}" class="bento-option">
                <div class="bento-content">
                    <img src="{{ $user->getProfilePicture()  }}" alt="Profile Picture" class="profile-picture">
                    <h3>{{ $user->username }}</h3>
                    <p>{{ $user->firstname }} {{ $user->lastname }}</p>
                </div>
            </a>
        @endforeach
    </div>

    <div class="pagination">
        {{ $users->links() }}
    </div>



   

@endsection