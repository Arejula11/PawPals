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
                    <span id="old-error" class="error" style="display: none;" aria-live="polite"></span>

                    <div class="form-group">
                        <label for="new">New Password</label>
                        <input type="password" class="form-control" id="new" name="new" placeholder="Your new password" required>
                    </div>
                    <span id="new-error" class="error" style="display: none;" aria-live="polite"></span>
                    <div class="form-group">
                        <label for="new">Repeat the Password</label>
                        <input type="password" class="form-control" id="repeat" name="repeat" placeholder="Your new password" required>
                    </div>
                    <span id="repeat-error" class="error" style="display: none;" aria-live="polite"></span>
                    @if(session('error'))
                        <div class="error">
                            {{ session('error') }}
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary">Change</button>
                </form>
            </div>

            <!-- Change privacity -->
            <div class="bento-item">
                <h2>Change Profile Privacy</h2>
                <p>Public Profile</p>
                <form action="{{ route('settings.users.public', ['id' => Auth::user()->id ]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <select class="form-control" id="public" name="public">
                        <option value="1" {{ $user->is_public ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ !$user->is_public ? 'selected' : '' }}>No</option>
                    </select>
                    <button type="submit" class="btn btn-primary" style="margin-top: 78px;">Change</button>
                </form>
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
    // Fields to validate
    const fields = ['old', 'new', 'repeat'];

    // Real-time validation function
    function validateField(field) {
        const input = document.getElementById(field);
        const errorElement = document.getElementById(`${field}-error`);

        // General validation for passwords
        if (field === 'old' || field === 'new') {
            if (input.value.trim() === '') {
                errorElement.textContent = `${field === 'old' ? 'Old' : 'New'} password is required.`;
                errorElement.style.display = 'block';
            } else if (input.value.trim().length < 8) {
                errorElement.textContent = `${field === 'old' ? 'Old' : 'New'} password must be at least 8 characters long.`;
                errorElement.style.display = 'block';
            } else {
                errorElement.textContent = '';
                errorElement.style.display = 'none';
            }
        }

        // Validation for "repeat" password
        if (field === 'repeat') {
            const newPassword = document.getElementById('new').value.trim();
            if (input.value.trim() === '') {
                errorElement.textContent = 'Please repeat the new password.';
                errorElement.style.display = 'block';
            } else if (input.value.trim() !== newPassword) {
                errorElement.textContent = 'Passwords do not match.';
                errorElement.style.display = 'block';
            } else {
                errorElement.textContent = '';
                errorElement.style.display = 'none';
            }
        }
    }

    // Attach blur event listeners to fields
    fields.forEach(function (field) {
        document.getElementById(field).addEventListener('blur', function () {
            validateField(field);
        });
    });

</script>

@endsection