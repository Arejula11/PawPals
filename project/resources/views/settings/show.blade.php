@extends('layouts.app')

@section('head')
    <link href="{{ asset('css/settings.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="settings-container">
        <h1>Settings</h1>
        <div class="bento">
            <!-- Change Password Section -->
            <div class="bento-item">
                <h2>Change Password</h2>
                <form action="{{ route('user.updatePassword', ['id' => Auth::user()->id ]) }}" method="POST" id="change-password-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="old">Old Password</label>
                        <input type="password" class="form-control" id="old" name="old" placeholder="Your old password" required>
                    </div>
                    <span id="old-error" class="error" style="display: none;"></span>

                    <div class="form-group">
                        <label for="new">New Password</label>
                        <input type="password" class="form-control" id="new" name="new" placeholder="Your new password" required>
                    </div>
                    <span id="new-error" class="error" style="display: none;"></span>

                    <button type="submit" class="btn btn-primary">Change</button>
                </form>
            </div>

            <!-- Change privacity -->
            <div class="bento-item">
                <h2>Change Profile Privacy</h2>
                <form action="{{ route('settings.users.public', ['id' => Auth::user()->id ]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <select class="form-control" id="public" name="public">
                        <option value="1" {{ $user->is_public ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ !$user->is_public ? 'selected' : '' }}>No</option>
                    </select>
                    <button type="submit" class="btn btn-primary" style="margin-top: 78px;">Change</button>
                </div>
            </div>

            <!-- Delete Account Section -->
            <div class="bento-item">
                <h2>Delete Account</h2>
                <form action="{{ route('settings.users.delete', ['id' => Auth::user()->id ]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
            
        </div>
    </div>
@endsection


@section('scripts')

<script>
    document.getElementById('change-password-form').addEventListener('submit', function(e) {
        let formValid = true;

        // Get the old and new passwords
        const oldPassword = document.getElementById('old').value.trim();
        const newPassword = document.getElementById('new').value.trim();
        
        // Get error message elements
        const oldErrorElement = document.getElementById('old-error');
        const newErrorElement = document.getElementById('new-error');

        // Reset error messages
        oldErrorElement.textContent = '';
        oldErrorElement.style.display = 'none';
        newErrorElement.textContent = '';
        newErrorElement.style.display = 'none';

        // Validate Old and New Passwords are both filled in
        if (oldPassword === '' || newPassword === '') {
            if (oldPassword === '') {
                oldErrorElement.textContent = 'Old password is required.';
                oldErrorElement.style.display = 'block';
            }
            if (newPassword === '') {
                newErrorElement.textContent = 'New password is required.';
                newErrorElement.style.display = 'block';
            }
            formValid = false;
        }

        // Validate passwords are at least 8 characters long
        if (oldPassword.length < 8) {
            oldErrorElement.textContent = 'Old password must be at least 8 characters long.';
            oldErrorElement.style.display = 'block';
            formValid = false;
        }
        if (newPassword.length < 8) {
            newErrorElement.textContent = 'New password must be at least 8 characters long.';
            newErrorElement.style.display = 'block';
            formValid = false;
        }

        // Validate that the new password is not the same as the old password
        if (oldPassword === newPassword) {
            newErrorElement.textContent = 'New password cannot be the same as the old password.';
            newErrorElement.style.display = 'block';
            formValid = false;
        }

        // If form is not valid, prevent submission
        if (!formValid) {
            e.preventDefault();
        }
    });
</script>

@endsection
