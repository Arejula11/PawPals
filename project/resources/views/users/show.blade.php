@extends('layouts.app')
@section('head')
    <!-- Incluir solo el CSS para esta vista -->
    <link href="{{ asset('css/viewProfile.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="user-profile">
    <!-- Header -->
    <header class="profile-header">
        <img src="{{ asset('images/profile.jpg') }}" alt="Profile Picture" class="profile-image">
        <div class="user-info">
            <h1 class="username">{{ $user->username }}</h1>
            <div class="follower-stats">
                <span><strong>{{ $user->followers_count }}</strong> Seguidores</span>
                <span><strong>{{ $user->following_count }}</strong> Seguidos</span>
            </div>
        </div>
    </header>

    <!-- Body -->
    <div class="profile-body">
        <!-- Columna izquierda (posts del usuario) -->
        <div class="posts">
            <h2>Publicaciones</h2>
            @foreach ($user->posts as $post)
                <div class="post-item">
                    <h3>{{ $post->title }}</h3>
                    <p>{{ $post->content }}</p>
                    <span class="post-date">Publicado el {{ $post->created_at->format('d M, Y') }}</span>
                </div>
            @endforeach
        </div>

        <!-- Columna derecha (grupos) -->
        <div class="groups">
            <h2>Grupos</h2>
            @if ($user->groups->isEmpty())
                <p>No pertenece a ningún grupo.</p>
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