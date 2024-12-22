@extends('layouts.app')
@section('head')
    <title>Register</title>
@endsection

@section('content')
<div class="register-part">
    <img src="{{ asset('images/pet_owner_photo.jpg') }}" alt="Pet_owner" class="register-img img-dog">
    <img src="{{ asset('images/cat_owner_photo.jpg') }}" alt="Cat_owner" class="register-img img-cat">
    <img src="{{ asset('images/parrot_owner_photo.jpg') }}" alt="Parrot_owner" class="register-img img-parrot">
    <img src="{{ asset('images/bird_owner_photo.jpg') }}" alt="Bird_owner" class="register-img img-bird">
    <div class="register-container" style="background-image: url('{{ asset('images/paws_photo.jpg') }}');">
        <form method="POST" action="{{ route('register') }}" class="register-form" enctype="multipart/form-data">
            {{ csrf_field() }}
            
            <label for="username">Username</label>
            <input id="username" type="text" name="username" value="{{ old('username') }}" placeholder="Your new username" required autofocus>
            <span id="username-error" class="error" style="display: none;"></span>
            @if ($errors->has('username'))
                <span class="error">{{ $errors->first('username') }}</span>
            @endif

            <label for="firstname">First Name</label>
            <input id="firstname" type="text" name="firstname" value="{{ old('firstname') }}" placeholder="Your first name" required>
            <span id="firstname-error" class="error" style="display: none;"></span>
            @if ($errors->has('firstname'))
                <span class="error">{{ $errors->first('firstname') }}</span>
            @endif

            <label for="surname">Surname</label>
            <input id="surname" type="text" name="surname" value="{{ old('surname') }}" placeholder="Your surname" required>
            <span id="surname-error" class="error" style="display: none;"></span>
            @if ($errors->has('surname'))
                <span class="error">{{ $errors->first('surname') }}</span>
            @endif

            <label for="email">E-Mail Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Your e-mail" required>
            <span id="email-error" class="error" style="display: none;"></span>
            @if ($errors->has('email'))
                <span class="error">{{ $errors->first('email') }}</span>
            @endif

            <label for="password">Password</label>
            <input id="password" type="password" name="password" placeholder="Your new password" required>
            <span id="password-error" class="error" style="display: none;"></span>
            @if ($errors->has('password'))
                <span class="error">{{ $errors->first('password') }}</span>
            @endif

            <label for="password-confirm">Confirm Password</label>
            <input id="password-confirm" type="password" name="password_confirmation" placeholder="Reapeat your password" required>
            <span id="password-confirm-error" class="error" style="display: none;"></span>

            <label for="bio_description">Bio Description</label>
            <textarea id="bio_description" name="bio_description" placeholder="Your bio description">{{ old('bio_description') }}</textarea>
            <span id="bio_description-error" class="error" style="display: none;"></span>
            @if ($errors->has('bio_description'))
                <span class="error">{{ $errors->first('bio_description') }}</span>
            @endif

            <label for="type">Profile Type</label>
            <select id="type" name="type" required>
                <option value="">Select your profile type</option>
                <option value="pet owner" {{ old('type') == 'pet owner' ? 'selected' : '' }}>Pet Owner</option>                <option value="veterinarian" {{ old('type') == 'veterinarian' ? 'selected' : '' }}>Veterinarian</option>
                <option value="adoption organization" {{ old('type') == 'adoption organization' ? 'selected' : '' }}>Adoption Organization</option>
                <option value="rescue organization" {{ old('type') == 'rescue organization' ? 'selected' : '' }}>Rescue Organization</option>
            </select>
            <span id="type-error" class="error" style="display: none;"></span>
            @if ($errors->has('type'))
                <span class="error">{{ $errors->first('type') }}</span>
            @endif

            <label for="is_public">
                <input id="is_public" type="checkbox" name="is_public" {{ old('is_public') ? 'checked' : '' }}>
                Make Profile Public
            </label>

            <label for="profile_picture">Profile Picture</label>
            <input type="file" class="form-control-file" id="profile_picture" name="profile_picture">
            
            <button type="submit">Register</button>
            <a class="button button-outline" href="{{ route('login') }}">Login</a>
        </form>
    </div>
</div>
@endsection

@section('scripts')

<script>
    // Real-time validation function
    function validateField(field) {
        const input = document.getElementById(field);
        const errorElement = document.getElementById(`${field}-error`);

        // Clear previous error message
        errorElement.textContent = '';
        errorElement.style.display = 'none';

        // Client-side validations
        if (field === 'username') {
            if (input.value.trim() === '') {
                errorElement.textContent = 'Username is required.';
                errorElement.style.display = 'block';
            }
        } else if (field === 'firstname') {
            if (input.value.trim() === '') {
                errorElement.textContent = 'First name is required.';
                errorElement.style.display = 'block';
            }
        } else if (field === 'surname') {
            if (input.value.trim() === '') {
                errorElement.textContent = 'Surname is required.';
                errorElement.style.display = 'block';
            }
        } else if (field === 'email') {
            if (input.value.trim() === '') {
                errorElement.textContent = 'Email is required.';
                errorElement.style.display = 'block';
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value)) {
                errorElement.textContent = 'Please enter a valid email address.';
                errorElement.style.display = 'block';
            }
        } else if (field === 'password') {
            if (input.value.trim() === '') {
                errorElement.textContent = 'Password is required.';
                errorElement.style.display = 'block';
            } else if (input.value.length < 8) {
                errorElement.textContent = 'Password must be at least 8 characters.';
                errorElement.style.display = 'block';
            }
        } else if (field === 'bio_description') {
            if (input.value.length > 255) {
                errorElement.textContent = 'Bio description cannot exceed 255 characters.';
                errorElement.style.display = 'block';
            }
        } else if (field === 'type') {
            if (input.value.trim() === '') {
                errorElement.textContent = 'Profile type is required.';
                errorElement.style.display = 'block';
            }
        }
    }

    // Password confirmation validation
    function validatePasswordConfirmation() {
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password-confirm').value;
        const passwordConfirmError = document.getElementById('password-confirm-error');

        // Clear previous error
        passwordConfirmError.textContent = '';
        passwordConfirmError.style.display = 'none';

        if (password !== passwordConfirm) {
            passwordConfirmError.textContent = 'Passwords do not match.';
            passwordConfirmError.style.display = 'block';
        }
    }

    // Attach blur event listeners to fields
    const fields = ['username', 'firstname', 'surname', 'email', 'password', 'bio_description', 'type'];
    fields.forEach(function (field) {
        document.getElementById(field).addEventListener("blur", function () {
            validateField(field);
        });
    });

    // Password confirmation validation on blur
    document.getElementById('password-confirm').addEventListener("blur", validatePasswordConfirmation);
</script>

@endsection
