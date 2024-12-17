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
                        <a href="{{ route('posts.show', ['id' => $notification->trigger_post_id]) }}">
                            
                            <p>{{ $notification->description }}</p>
                            @php
                                $post = \App\Models\Post::find($notification->trigger_post_id);
                            @endphp
                            <p>Post: {{ $post->description }}</p>
                            <p>{{ $notification->date }}</p>
                        </a>
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
                        @php 
                            $comment = \App\Models\Comment::find($notification->trigger_comment_id);
                            $post = \App\Models\Post::find($comment->post_id);
                        @endphp
                        <a href="{{ route('posts.show', ['id' => $post->id]) }}">
                            
                            <p>{{ $notification->description }}</p>
                            @php
                                $comment = \App\Models\Comment::find($notification->trigger_comment_id);
                            @endphp
                            <p>Comment: {{ $comment->content }}</p>
                            <p>{{ $notification->date }}</p>
                        </a>
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
                        <a href="{{ route('groups.show', ['id' => $notification->trigger_group_id]) }}">   
                            <p>{{ $notification->description }}</p>
                            @php
                                $group = \App\Models\Group::find($notification->trigger_group_id);
                            @endphp
                            <p>Group: {{ $group->name }}</p>
                            <p>{{ $notification->date }}</p>
                        </div>
                    </a>
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
                        <a href="{{ route('groups.messages', ['id' => $notification->trigger_group_id]) }}">   
                            
                            <p>{{ $notification->description }}</p>
                            @php
                                $group = \App\Models\Group::find($notification->trigger_group_id);
                            @endphp
                            <p>Group: {{ $group->name }}</p>
                            <p>{{ $notification->date }}</p>
                        </a>
                    </div>
                @empty
                    <p>No notifications found.</p>
                @endforelse
            </div>
        </div>

        <div class="notification-box">
            <h2>User Notifications</h2>
            <div class="notification-content">
                @forelse($userNotifications as $notification)
                    <div class="notification">
                        <a href="{{ route('users.show', ['id' => $notification->trigger_user_id]) }}">   
                            <p>{{ $notification->description }}</p>
                            @php
                                $user = \App\Models\User::find($notification->trigger_user_id);
                            @endphp
                            <p>User: {{ $user->username }}</p>
                            <p>{{ $notification->date }}</p>
                        </a>
                    </div>
                @empty
                    <p>No notifications found.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection