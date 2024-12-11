@extends('layouts.app')
@section('head')
    <link href="{{ asset('css/createPost.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">

    <h1>Edit Post</h1>
    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="desc">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" required>{{ old('description', $post->description) }}</textarea>
        </div>

        <div class="pic">
            <label for="post_picture" class="form-label">Upload Picture</label>
            <input type="file" id="post_picture" name="post_picture" class="form-control">
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
                        {{ $tag->user->firstname }} (@ {{ $tag->user->username }})
                        <button type="button" class="remove-tag" data-id="{{ $tag->user->id }}">Remove</button>
                    </div>
                    @endif
                @endforeach
                {{-- Tagged users will be displayed here dynamically --}}
            </div>
            <input type="hidden" name="tagged_users" id="tagged-users-input" value="{{ $post->tags->pluck('user_id')->join(',') }}">
        </div>

        {{-- Search for Users --}}
        <div class="search-part">
            <input type="text" name="query" id="search-input" placeholder="Search for users..." autocomplete="off" />
            <div id="user-results"></div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary" style="width: auto; height: auto; font-size: 0.9rem; padding: 8px 16px;">Update Post</button>
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
                            addTaggedUser(user.id, user.first_name, user.username);
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

    function addTaggedUser(id, name, username) {
        let taggedUsersList = document.getElementById('tagged-users-list');
        let taggedUsersInput = document.getElementById('tagged-users-input');

        if (!document.querySelector(`#tagged-user-${id}`)) {
            let userTag = document.createElement('div');
            userTag.classList.add('tagged-user');
            userTag.id = `tagged-user-${id}`;
            userTag.innerHTML = `
                ${name} (@${username})
                <button type="button" class="remove-tag" data-id="${id}">Remove</button>
            `;

            userTag.querySelector('.remove-tag').addEventListener('click', function() {
                userTag.remove();
                updateTaggedUsersInput();
            });

            taggedUsersList.appendChild(userTag);

            updateTaggedUsersInput();
        }
    }

    function updateTaggedUsersInput() {
        let taggedUsersList = document.querySelectorAll('.tagged-user');
        let taggedUsersInput = document.getElementById('tagged-users-input');

        let userIds = Array.from(taggedUsersList).map(user => {
            return user.id.replace('tagged-user-', '');
        });

        taggedUsersInput.value = userIds.join(',');
    }

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
