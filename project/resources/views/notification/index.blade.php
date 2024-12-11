@extends('layouts.app')

@section('head')
    <link href="{{ asset('css/notification.css') }}" rel="stylesheet">
@endsection

@section('content')
    <h1>Notifications center</h1>
    @php
        $userNotifications = auth()->user()->userNotifications;
        $postNotifications = auth()->user()->postNotifications;
        $commentNotifications = auth()->user()->commentNotifications;
        $groupOwnerNotifications = auth()->user()->groupOwnerNotifications;
        $groupMemberNotifications = auth()->user()->groupMemberNotifications;
    @endphp
    @if($groupMemberNotifications->count() > 0)
        <div class="notification-container">
            <div class="user-not">
                <h2>User notifications</h2>
                @foreach($userNotifications as $notification)
                    <div class="notification">
                        <div class="notification-content">
                            <p>{{ $notification->description }}</p>
                            <p>{{ $notification->date }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="user-not">
                <h2>Post notifications</h2>
                @foreach($postNotifications as $notification)
                    <div class="notification">
                        <div class="notification-content">
                            <p>{{ $notification->description }}</p>
                            <p>{{ $notification->date }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="user-not">
                <h2>Comment notifications</h2>
                @foreach($commentNotifications as $notification)
                    <div class="notification">
                        <div class="notification-content">
                            <p>{{ $notification->description }}</p>
                            <p>{{ $notification->date }}</p>
                        </div>
                    </div>
                @endforeach
            </div>


        </div>
    @else
        <p>No notifications found</p>
    @endif
@endsection
