@extends('layouts.app')
@section('head')
    <title>About Page</title>
@endsection

@section('head')

<link href="{{ asset('css/static.css') }}" rel="stylesheet">

@endsection

@section('content')
<h2>About us</h2>

<p>PawPals is an online platform for pet owners to connect, share experiences, and discuss pet care. Users can share daily pet activities, engage in discussions on training, health, and care, and find others with similar pets or walking routes. The platform also supports pet adoption and rescue connections, fostering a vibrant pet community.</p>

<h2>Your Admins</h2>

<p>Meet our admins, who want to make your experience with PawPawls pleasant and ensure secure communciation and networking.</p>


<div class="admin-container">
    <div class="admin-picture">
        <img src="{{ asset('images/admin/diogo.jpeg') }}" alt="Profile Picture" class="admin-img">
        <p>Diogo Rocha</p>
    </div>
    <div class="admin-picture">
        <img src="{{ asset('images/admin/miguel.jpeg') }}" alt="Profile Picture" class="admin-img">
        <p>Miguel Aréjula Aísa</p>
    </div>
    <div class="admin-picture">
        <img src="{{ asset('images/admin/paula.jpg') }}" alt="Profile Picture" class="admin-img">
        <p>Paula Frindte</p>
    </div>
    <div class="admin-picture">
        <img src="{{ asset('images/admin/rodrigo.jpg') }}" alt="Profile Picture" class="admin-img">
        <p>Rodrigo Rodrigues</p>
    </div>
</div>


@endsection