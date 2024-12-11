@extends('layouts.app')

@section('head')
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection
@section('content')

@if (Auth::check())
    <div="container">
        <div class="timeline"> 
            <h1>Public</h1>
            <h1>Following</h1>        
        </div>
        <div class="post-gallery-home" data-loading="false">
            @foreach ($posts as $post)
                @include('pages.post-list', ['post' => $post])
            @endforeach
        </div>
        <div class="pagination-container">
            {{ $posts->links() }}
        </div>
    </div>
    
    <form action="{{ route('requests.show') }}" method="GET" style="position: absolute; top: 15px; right: 70px;">
        <button type="button" style="background-color:white; border:none">
            <img src="/images/follow.png" alt="Follow Requests" class="requests-follow">
            @if ($pendingRequestsCount > 0)
                <span class="badge" style="text-align:center">{{ $pendingRequestsCount }}</span>
            @endif
        </button>
    </form>


    <div class="follow-container">
        <h1>Requests to follow</h1>
        <div class="scrollable-content">
            @foreach ($pendingRequests as $request)
                <div class="request-item">
                    <a href="{{ route('users.show', $request->follower->id) }}" class="user-link">
                        <img class="profile-picture-request" src="profile/{{ $request->follower->profile_picture }}" alt="{{ $request->follower->username }}'s profile picture">
                    </a>
                    <p class="request-phrase">{{ $request->follower->username }}</p>
                    <form action="{{ route('follow.accept', ['user1_id' => $request->follower->id, 'user2_id' => $request->user2_id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="request-accept">
                            <img src="/images/accept.png" alt="Follow accepted">
                        </button>
                    </form>
                    <form action="{{ route('follow.reject', ['user1_id' => $request->follower->id, 'user2_id' => $request->user2_id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="request-decline">
                            <img src="/images/decline.png" alt="Follow declined">
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>


    <div class="profile-picture" style="position: absolute; top: 10px; right: 10px;">
        <a href="{{ route('users.show', ['id' => Auth::user()->id]) }}">
            <img src="{{ Auth::user()->getProfilePicture() }}" alt="Profile Picture" class="profile-img">
        </a>
    </div>


@else
    <div="container"> Hello, Stranger!</div>
@endif
@endsection
@section('scripts')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const followButton = document.querySelector(".requests-follow");
    const followContainer = document.querySelector(".follow-container");

    followButton.addEventListener("click", function (event) {
    event.preventDefault(); // Prevent form submission
    followContainer.classList.toggle("active");
    });
});

// Tag script
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.show-tagged-users-icon').forEach(icon => {
        icon.addEventListener('click', function () {
            const taggedUsersList = this.nextElementSibling;
            if (taggedUsersList) {
                taggedUsersList.classList.toggle('hidden');
                taggedUsersList.classList.toggle('visible');
            }
        });
    });

    document.addEventListener('click', function (event) {
        document.querySelectorAll('.tagged-users-list').forEach(list => {
            if (!list.contains(event.target) && !list.previousElementSibling.contains(event.target)) {
                list.classList.add('hidden');
                list.classList.remove('visible');
            }
        });
    });
});
//

// Post Like button script
document.addEventListener('DOMContentLoaded', function () {
    const postGallery = document.querySelector('.post-gallery-home');

    // Use event delegation to handle clicks on like buttons
    postGallery.addEventListener('click', function (event) {
        const button = event.target.closest('.like-button'); // Check if a like button was clicked
        if (!button) return;

        const postId = button.getAttribute('data-post-id');
        const isLiked = button.getAttribute('data-liked') === 'true';
        const url = isLiked
            ? `/posts/${postId}/likes/destroy`
            : `/posts/${postId}/likes/store`;
        const method = isLiked ? 'DELETE' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update like state and count
                    button.setAttribute('data-liked', !isLiked);
                    button.querySelector('i').classList.toggle('fas', !isLiked);
                    button.querySelector('i').classList.toggle('far', isLiked);
                    button.querySelector('.like-count').textContent = data.likeCount;
                } else {
                    console.error('Error liking/unliking the post:', data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    });
});
//

</script>
@endsection