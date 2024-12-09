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
    document.getElementById('search-input').addEventListener('input', function() {
        let query = this.value;

        if (query.length >= 2) {
            fetch(`/search-users?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    let resultsDiv = document.getElementById('user-results');
                    resultsDiv.innerHTML = '';

                    if (data.length > 0) {
                        data.forEach(user => {
                            let userDiv = document.createElement('div');
                            userDiv.classList.add('user-result');

                            userDiv.innerHTML = `
                                <div class="user-link" data-id="${user.id}" data-name="${user.first_name}" data-username="${user.username}">
                                    <img src="${user.profile_picture}" alt="${user.username}'s profile image" class="profile-image" />
                                    <div class="user-info">
                                        <span class="first-name">${user.first_name}</span>
                                        <span class="username">@${user.username}</span>
                                    </div>
                                </div>
                            `;

                            // Add click event listener for tagging
                            userDiv.addEventListener('click', function() {
                                addTaggedUser(user.id, user.first_name, user.username);
                            });

                            resultsDiv.appendChild(userDiv);
                        });
                    } else {
                        resultsDiv.innerHTML = 'No users found.';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('user-results').innerHTML = 'There was an error with the search.';
                });
        } else {
            document.getElementById('user-results').innerHTML = '';
        }
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
