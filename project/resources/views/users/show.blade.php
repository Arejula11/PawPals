@extends('layouts.app')
@section('head')

    <link href="{{ asset('css/viewProfile.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="user-profile">
    <!-- Header -->
    <header class="profile-header">

        <img src="{{ $user->getProfilePicture()  }}" alt="Profile Picture" class="profile-image">
        <div class="user-info">
            <h1 class="username">{{ $user->username }}</h1>
            <h3>{{ $user->firstname }} {{ $user->surname }}</h2>
            <h3>{{ $user->type}}</h2>
            <div class="follower-stats">
                <span><strong> {{ $user->followers()->count() }}</strong> Followers</span>
                <span><strong>{{ $user->follows()->count() }}</strong> Following</span>
            </div>
            <div class="my-followers">
                @foreach ($user->followers() as $userfollower)
                    <p>found user</p>
                @endforeach

            </div>
            <span><strong>About me:</strong></span>
            <p class="profile-description">{{ $user->bio_description }}</p>
            @if ($isOwnProfile)
                <a href="{{ route('users.update', $user->id) }}" class="btn btn-primary">Edit Profile</a>
            @else
                @if ($followStatus === 'pending')
                    <button id="cancel-btn" class="btn btn-secondary">Request Sent</button>
                @elseif ($followStatus === 'accepted')
                    <button id="following-btn" class="btn btn-success">Following</button>
                @else
                    <form method="POST" action="{{ route('follow.send') }}">
                        @csrf
                        <input type="hidden" name="user1_id" value="{{ Auth::id() }}">
                        <input type="hidden" name="user2_id" value="{{ $user->id }}">

                        @if ($user->is_public)
                            <button type="submit" class="btn btn-primary">Follow</button>
                        @else
                            @if ($followStatus === 'pending')
                                <button class="btn btn-secondary" disabled>Request Sent</button>
                            @elseif ($followStatus === 'accepted')
                                <button class="btn btn-success" disabled>Following</button>
                            @else
                                <button type="submit" class="btn btn-primary">Send Follow Request</button>
                            @endif
                        @endif
                    </form>
                @endif
            @endif
        </div>
    </header>

    <!-- Unfollow Confirmation Modal -->
    <div id="unfollow-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <p>Would you like to unfollow?</p>
            <div class="modal-actions">
                <button id="unfollow-yes" class="btn btn-danger">Yes</button>
                <button id="unfollow-no" class="btn btn-secondary">No</button>
            </div>
        </div>
    </div>

    <!-- Cancel Request Modal -->
    <div id="cancel-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <p>Would you like to cancel the follow request?</p>
            <div class="modal-actions">
                <button id="cancel-yes" class="btn btn-danger">Yes</button>
                <button id="cancel-no" class="btn btn-secondary">No</button>
            </div>
        </div>
    </div>

    <!-- Body -->
    <div class="profile-body">
        <!-- Left column (user's posts) -->
        <div class="posts">
            <h2>Posts</h2>
            <div class="post-gallery-show">
                @foreach ($postImages as $image)
                    <div class="post-item-show">
                        <img src="{{ asset($image) }}" alt="Post Image">
                    </div>
                @endforeach
            </div>
            
        </div>
        </div>

        <!-- Right column (groups) -->
        <div class="groups">
            <h2>Groups</h2>
            @if ($user->groups->isEmpty())
                <p>Does not belong to any group.</p>
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
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const followingBtn = document.getElementById('following-btn');
        const modal = document.getElementById('unfollow-modal');
        const unfollowYesBtn = document.getElementById('unfollow-yes');
        const unfollowNoBtn = document.getElementById('unfollow-no');

        const cancelBtn = document.getElementById('cancel-btn');
        const cancelmodal = document.getElementById('cancel-modal');
        const cancelYesBtn = document.getElementById('cancel-yes');
        const cancelNoBtn = document.getElementById('cancel-no');

        // Create and add overlay element
        const overlay = document.createElement('div');
        overlay.classList.add('overlay');
        document.body.appendChild(overlay);

        // Function to show modal with animation
        function showModal() {
            modal.style.display = 'block';
            overlay.style.display = 'block';
            setTimeout(() => {
                modal.style.opacity = '1';
                modal.style.transform = 'translate(-50%, -50%) scale(1)';
            }, 10);
        }

        function showModal2() {
            cancelmodal.style.display = 'block';
            overlay.style.display = 'block';
            setTimeout(() => {
                cancelmodal.style.opacity = '1';
                cancelmodal.style.transform = 'translate(-50%, -50%) scale(1)';
            }, 10);
        }

        // Function to hide modal with animation
        function hideModal() {
            modal.style.opacity = '0';
            modal.style.transform = 'translate(-50%, -50%) scale(0.8)';
            setTimeout(() => {
                modal.style.display = 'none';
                overlay.style.display = 'none';
            }, 300);
        }

        function hideModal2() {
            cancelmodal.style.opacity = '0';
            cancelmodal.style.transform = 'translate(-50%, -50%) scale(0.8)';
            setTimeout(() => {
                cancelmodal.style.display = 'none';
                overlay.style.display = 'none';
            }, 300);
        }

        // Show modal when "Following" button is clicked
        followingBtn?.addEventListener('click', showModal);
        cancelBtn?.addEventListener('click', showModal2);

        // Hide modal on "No" click
        unfollowNoBtn.addEventListener('click', hideModal);
        cancelNoBtn.addEventListener('click', hideModal2);

        // Handle "Yes" action
        unfollowYesBtn.addEventListener('click', () => {
            fetch("{{ route('follow.remove') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    user1_id: {{ Auth::id() }},
                    user2_id: {{ $user->id }}
                })
            }).then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Failed to unfollow. Please try again.');
                    hideModal();
                }
            });
        });

        cancelYesBtn.addEventListener('click', () => {
            fetch("{{ route('follow.remove') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    user1_id: {{ Auth::id() }},
                    user2_id: {{ $user->id }}
                })
            }).then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Failed to unfollow. Please try again.');
                    hideModal2();
                }
            });
        });
    });
</script>
@endsection