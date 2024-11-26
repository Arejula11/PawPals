@extends('layouts.app')

@section('content')

@if (Auth::check())
    <div="container"> Hello, {{ Auth::user()->username }}!</div>
    

    <div class="post-gallery">
        @foreach ($postImages as $image)
            <div class="post-item">
                <img src="{{ $image }}" alt="Post Image">
            </div>
        @endforeach
    </div>

    <div class="profile-picture" style="position: absolute; top: 10px; right: 10px;">
        <a href="{{ route('users.show', ['id' => Auth::user()->id]) }}">
            <img src="{{ Auth::user()->profilePicture() }}" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;">
        </a>
    </div>


@else
    <div="container"> Hello, Stranger!</div>
@endif
@endsection