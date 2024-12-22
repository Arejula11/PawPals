@extends('layouts.app')
@section('head')
    <title>{{ $user->username }}'s Profile</title>
    <link href="{{ asset('css/viewProfile.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="user-profile">
    <!-- Header -->
    <header class="profile-header">

        <img src="{{ $user->getProfilePicture()  }}" alt="Profile Picture" class="profile-image">
        <div class="user-info">
            <h1 class="username">{{ $user->username }}</h1>
            <h3>{{ $user->firstname }} {{ $user->surname }}</h3>
            <h3>{{ $user->type}}</h3>
            <div class="follower-stats" id="followers-toggle">
                <span id="followers-count" style="cursor: pointer;"><strong>{{ $user->followers()->count() }}</strong> Followers</span>
                <span id="follows-count" style="cursor: pointer;"><strong>{{ $user->follows()->count() }}</strong> Following</span>
                <span id="posts-count" style="cursor: pointer;"><strong>{{ count($postImages) }}</strong> Posts</span>
                <div class="my-followers" id="followers-list">
                    @foreach ($user->followers as $userfollower)
                        <a href="{{ route('users.show', $userfollower->id) }}" class="user-link-profile">
                            <img src="{{ asset('./profile/' . ($userfollower->profile_picture ?? './profile/default.png')) }}" 
                                alt="{{ $userfollower->firstname }}'s profile image" 
                                class="profile-image-profile" />
                            <div class="user-info-profile">
                                <span class="first-name-follower">{{ $userfollower->firstname ?? 'Anonymous' }}</span>
                                <span class="username-follower">@ {{ $userfollower->username ?? 'unknown' }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="my-follows" id="follow-list">
                    @foreach ($user->follows as $userfollow)
                        <a href="{{ route('users.show', $userfollow->id) }}" class="user-link-profile">
                            <img src="{{ asset('./profile/' . ($userfollow->profile_picture ?? './profile/default.png')) }}" 
                                alt="{{ $userfollow->firstname }}'s profile image" 
                                class="profile-image-profile" />
                            <div class="user-info-profile">
                                <span class="first-name-follower">{{ $userfollow->firstname ?? 'Anonymous' }}</span>
                                <span class="username-follower">@ {{ $userfollow->username ?? 'unknown' }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
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
                @if($user->is_public || auth()->user()->follows->contains($user) || auth()->id() === $user->id)
                <div class="post-gallery-show">
                    @foreach ($postImages as $postImage)
                        <div class="post-item-show">
                            <a href="{{ route('posts.show', $postImage['id']) }}">
                                <img src="{{ asset($postImage['image']) }}" alt="Post Image">
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p>This profile is private</p>
            @endif
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

    document.getElementById('followers-count').addEventListener('click', () => {
        const followersList = document.getElementById('followers-list');
        followersList.style.display = (followersList.style.display === 'block') ? 'none' : 'block';
    });

    document.getElementById('follows-count').addEventListener('click', () => {
        const followsList = document.getElementById('follow-list');
        followsList.style.display = (followsList.style.display === 'block') ? 'none' : 'block';
    });

</script>
@endsection