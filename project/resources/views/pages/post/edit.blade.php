@extends('layouts.app')
@section('head')
    <link href="{{ asset('css/createPost.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('content')
<div class="container-post">

    <h1>Edit Post</h1>
    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="desc">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" required>{{ old('description', $post->description) }}</textarea>
        </div>

        <div class="privacy">  
            <label for="is_public" class="form-label">Is Public:</label>
            <select name="is_public" id="is_public" class="form-select">
                <option value="1" {{ $post->is_public ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ !$post->is_public ? 'selected' : '' }}>No</option>
            </select>
        </div>

        {{-- Tagged Users Section --}}
        <label for="tagged_users" class="form-label">Tagged Users:</label>
        <div class="tagged">
            <div id="tagged-users-list" class="tagged-users">
                @foreach ($post->tags as $tag)
                    @if ($tag->user)
                    <div id="tagged-user-{{ $tag->user->id }}" class="tagged-user">
                        @ {{ $tag->user->username }}
                        <button type="button" class="remove-tag" data-id="{{ $tag->user->id }}">Remove</button>
                    </div>
                    @endif
                @endforeach
                {{-- Tagged users will be displayed here dynamically --}}
            </div>
            <input type="hidden" name="tagged_users" id="tagged-users-input" value="{{ $post->tags->pluck('user_id')->join(',') }}">
        </div>

        <div class="search-part">
            <input type="text" name="query" id="search-input" placeholder="Search for users..." autocomplete="off" />
            <div id="user-results"></div>
        </div>

        <div class="button-update">
            <button type="submit" class="btn btn-primary">Update Post</button>
        </div>
    </form>
    <div class="button-delete"> 
        <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?')">Delete Post</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-input');
    const resultsDiv = document.getElementById('user-results');

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

document.querySelectorAll('.remove-tag').forEach(button => {
    button.addEventListener('click', function() {
        let userTag = document.getElementById(`tagged-user-${this.dataset.id}`);
        if (userTag) {
            userTag.remove();
            updateTaggedUsersInput();
        }
    });
});

</script>
@endsection
