@extends('layouts.admin')
@section('head')
    <title>Admin Home</title>
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="header">
        <a href="{{route('admin.home')}}" class="name">PetPawls</a>

        <h1>Hello {{ Auth::user()->username }} to your admin panel</h1>
        <p>What would you like to do?</p>
    </div>

    <div class="admin-options">
        <a href="{{ route('admin.users.create')}}" class="bento-option">
            <div class="bento-content">
                <h3>Create New Admin User</h3>
                <p>Creats a new user accounts for an administrator.</p>
            </div>
        </a>
        <a href="{{ route('admin.users.manage') }}" class="bento-option">
            <div class="bento-content">
                <h3>Manage Users</h3>
                <p>Edit and delete users accounts.</p>
            </div>
        </a>
        <a href="{{ route('admin.groups.manage') }}" class="bento-option">
            <div class="bento-content">
                <h3>Manage Groups</h3>
                <p>Edit and delete user groups.</p>
            </div>
        </a>
        <a href="{{ route('admin.posts.manage') }}" class="bento-option">
            <div class="bento-content">
                <h3>Manage Posts</h3>
                <p>Edit and delete user posts and their comments.</p>
            </div>
        </a>
        <a href="{{ route('admin.bans') }}" class="bento-option">
            <div class="bento-content">
                <h3>Manage Bans</h3>
                <p>Create bans, remove bans and manage the appeals for the bans.</p>
            </div>
        </a>
        <a href="{{ route('admin.changePassword') }}" class="bento-option">
            <div class="bento-content">
                <h3>Change password</h3>
                <p>Change your password.</p>
            </div>
        </a>
    </div>
    <div class="logout">
        <a class="button logout" style="width:200px;" href="{{ url('/logout') }}"> Logout </a>
    </div>

@endsection