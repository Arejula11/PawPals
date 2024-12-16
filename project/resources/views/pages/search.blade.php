@extends('layouts.app')

@section('content')
    <div class="search-part">
        <input type="text" name="query" id="search-input" placeholder="Search for users..." autocomplete="off"/>
        <select class="input-type" id="user-type-select">
            <option value="all" selected>All Types</option>
            <option value="pet owner">Pet Owner</option>
            <option value="veterinarian">Veterinarian</option>
            <option value="adoption organization">Adoption Organization</option>
            <option value="rescue organization">Rescue Organization</option>
        </select>
        <div id="user-results"></div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const searchInput = document.getElementById('search-input');
                const userTypeSelect = document.getElementById('user-type-select');
                const resultsDiv = document.getElementById('user-results');

                // Debounce function to avoid frequent calls
                let debounceTimer;
                function debounce(func, delay) {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(func, delay);
                }

                function fetchUsers() {
                    const query = searchInput.value.trim();
                    const userType = userTypeSelect.value;

                    fetch(`/search-users?query=${encodeURIComponent(query)}&type=${encodeURIComponent(userType)}`)
                        .then(response => response.json())
                        .then(data => {
                            resultsDiv.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(user => {
                                    const userDiv = document.createElement('div');
                                    userDiv.classList.add('user-result');
                                    userDiv.innerHTML = `
                                        <a href="${user.profile_url}" class="user-link">
                                            <img src="${user.profile_image}" alt="${user.username}'s profile image" class="profile-image" />
                                            <div class="user-info">
                                                <span class="first-name">${user.first_name}</span>
                                                <span class="username">@${user.username}</span>
                                            </div>
                                        </a>
                                    `;
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
                userTypeSelect.addEventListener('change', fetchUsers);
            });
        </script>
    </div>

    <style>
        #search-input{
            width: 70%;
        }

        .input-type{
            width: auto;
        }

        .user-result {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .user-link {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: inherit;
        }

        .profile-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .first-name {
            font-weight: bold;
        }

        .username {
            color: gray;
        }
    </style>
@endsection
