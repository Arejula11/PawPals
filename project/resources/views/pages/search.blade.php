@extends('layouts.app')

@section('content')
    <div class="search-part">
        <input type="text" name="query" id="search-input" placeholder="Search for users..." autocomplete="off"/>
        <div id="user-results"></div>

        <script>
            document.getElementById('search-input').addEventListener('input', function() {
                let query = this.value;

                if (query.length >= 2) {
                    // Make an AJAX request to get the matching users
                    fetch(`/search-users?query=${query}`)
                        .then(response => response.json())
                        .then(data => {
                            let resultsDiv = document.getElementById('user-results');
                            resultsDiv.innerHTML = '';
                            
                            if (data.length > 0) {
                                data.forEach(user => {
                                    let userDiv = document.createElement('div');
                                    userDiv.classList.add('user-result');

                                    // Add the user's profile image, first name, and username
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
        </script>
    </div>

    <style>
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
