@extends('layouts.app')
@section('head')

    <link href="{{ asset('css/viewProfile.css') }}" rel="stylesheet">
@endsection

@section('content')
<div>
    <h1>Admin Panel</h1>
    <p>Here you can manage users and groups.</p>
    <div class="admin-panel">
        <div class="users">
            <h2>Users</h2>
            <ul>
                @foreach ($users as $user)
                    <li><a href="{{ route('users.show', $user->id) }}">{{ $user->username }}</a></li>
                @endforeach
            </ul>
        </div>
       <h1>Search user</h1>

    </div>
@endsection