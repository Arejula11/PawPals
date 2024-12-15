@extends('layouts.app')
@section('head')

<link href="{{ asset('css/static.css') }}" rel="stylesheet">
@endsection
@section('content')

<h2>About us</h2>
<p>PawPals is an online platform for pet owners to connect, share experiences, and discuss pet care. Users can share daily pet activities, engage in discussions on training, health, and care, and find others with similar pets or walking routes. The platform also supports pet adoption and rescue connections, fostering a vibrant pet community.</p>

<h2>Your Admins</h2>
<p>Meet our admins, who want to make your experience with PawPawls pleasant and ensure secure communciation and networking.</p>

<div class="profile-picture">
    <a href="{{ route('users.show', ['id' => Auth::user()->id]) }}">
        <img src="{{ Auth::user()->getProfilePicture() }}" alt="Profile Picture" class="profile-img">
    </a>
</div>

@endsection