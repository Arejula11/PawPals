@extends('layouts.admin')
@section('head')
    <title>Show All Bans</title>
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="header">
        <a href="{{route('admin.home')}}" class="name">PetPawls</a>
    </div>

    <h1 style="font-size: 32px; margin-bottom: 10px; margin-top: -40px; text-align: center; color: #333; font-weight: semi-bold;">Manage bans</h1>
    <div class="bans">
        <div class="bans-header">Ban List</div>
        @foreach($bans as $ban)
            <div class="ban">
                <a href="{{ route('admin.bans.show', $ban->id) }}">
                    <div class="ban-details">
                        <p class="ban-mete">Username: {{ $ban->user->username }}</p>
                        <p class="ban-reason">Reason: {{ $ban->reason }}</p>
                        <p class="ban-meta">Date: {{ $ban->date }}</p>
                        <p class="ban-meta">User ID: {{ $ban->user_id }}</p>
                    </div>
                    <div class="ban-active {{ !$ban->active ? 'active' : 'inactive' }}">
                        {{ !$ban->active ? 'Unbanned' : 'Banned' }}
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection