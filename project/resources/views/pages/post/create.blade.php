@extends('layouts.app')
@section('head')
    <link href="{{ asset('css/createPost.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container-post">

    <h1>Create Post</h1>
    {{-- Post Creation Form --}}
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="desc">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" placeholder="Post description" required>{{ old('description') }}</textarea>
        </div>

        <div class="pic">
            <label for="post_picture" class="form-label">Upload Picture</label>
            <input type="file" id="post_picture" name="post_picture" class="form-control">
        </div>

        <label for="tagged_users" class="form-label">Tagged Users:</label>
        <div class="tagged">
            <div id="tagged-users-list" class="tagged-users">
            </div>
            <input type="hidden" name="tagged_users" id="tagged-users-input" value="">
        </div>

        <div class="search-part">
            <input type="text" name="query" id="search-input" placeholder="Search for users..." autocomplete="off" />
            <div id="user-results"></div>
        </div>

        <div class="button-create">
            <button type="submit" class="btn btn-primary">Create Post</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-input');
    const resultsDiv = document.getElementById('user-results');

    // Debounce function to avoid frequent calls
    let debounceTimer;
    function debounce(func, delay) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(func, delay);
    }

    function fetchUsers() {
        const query = searchInput.value.trim();

        fetch(`/search-users?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                resultsDiv.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(user => {
                        const userDiv = document.createElement('div');
                        userDiv.classList.add('user-result');
                        userDiv.innerHTML = `
                            <div class="user-link" data-id="${user.id}" data-name="${user.first_name}" data-username="${user.username}">
                                <img src="${user.profile_image}" alt="${user.username}'s profile image" class="profile-image" />
                                <div class="user-info">
                                    <span class="first-name">${user.first_name}</span>
                                    <span class="username">@${user.username}</span>
                                </div>
                            </div>
                        `;
                        userDiv.addEventListener('click', function() {
                            addTaggedUser(user.id, user.username);
                        });
                        resultsDiv.appendChild(userDiv);
                    });
                } else {
                    resultsDiv.innerHTML = '<p>No users found.</p>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resultsDiv.innerHTML = '<p>There was an error with the search.</p>';
            });
    }

    searchInput.addEventListener('input', () => debounce(fetchUsers, 300));
});
</script>
@endsection

