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
                                    userDiv.textContent = user.username;
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
@endsection
