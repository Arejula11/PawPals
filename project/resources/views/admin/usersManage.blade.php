@extends('layouts.admin')
@section('head')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="header">
        <a href="{{route('admin.home')}}" class="name">PetPawls</a>
        <h1>Users</h1>
    </div>
    <div class="user-bento">
        @foreach($users as $user)
            <a href="{{ url('admin/users/' . $user->id) }}" class="bento-option">
                <div class="bento-content">
                    <img src="{{ $user->getProfilePicture()  }}" alt="Profile Picture" class="profile-picture">
                    <h3>{{ $user->username }}</h3>
                    <p>{{ $user->firstname }} {{ $user->lastname }}</p>
                    @if($user->isBanned())
                        <p class="banned" style="color: red; font-weight: bold;">Banned</p>
                    @endif
                </div>
            </a>
        @endforeach
    </div>

    <div class="pagination">
        {{ $users->links() }}
    </div>



   

@endsection