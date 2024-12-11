@extends('layouts.app')

@section('head')
    <link href="{{ asset('css/notification.css') }}" rel="stylesheet">
@endsection

@section('content')
    <h1>Notifications Center</h1>
    <div class="notification-container">
        <div class="notification-box">
            <h2>Post Notifications</h2>
            <div class="notification-content">
                @forelse($postNotifications as $notification)
                    <div class="notification">
                        <p>{{ $notification->description }}</p>
                        <p>{{ $notification->date }}</p>
                    </div>
                @empty
                    <p>No notifications found.</p>
                @endforelse
            </div>
        </div>

        <div class="notification-box">
            <h2>Comment Notifications</h2>
            <div class="notification-content">
                @forelse($commentNotifications as $notification)
                    <div class="notification">
                        <p>{{ $notification->description }}</p>
                        <p>{{ $notification->date }}</p>
                    </div>
                @empty
                    <p>No notifications found.</p>
                @endforelse
            </div>
        </div>

        <div class="notification-box">
            <h2>Group Owner Notifications</h2>
            <div class="notification-content">
                @forelse($groupOwnerNotifications as $notification)
                    <div class="notification">
                        <p>{{ $notification->description }}</p>
                        <p>{{ $notification->date }}</p>
                    </div>
                @empty
                    <p>No notifications found.</p>
                @endforelse
            </div>
        </div>

        <div class="notification-box">
            <h2>Group Member Notifications</h2>
            <div class="notification-content">
                @forelse($groupMemberNotifications as $notification)
                    <div class="notification">
                        <p>{{ $notification->description }}</p>
                        <p>{{ $notification->date }}</p>
                    </div>
                @empty
                    <p>No notifications found.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection