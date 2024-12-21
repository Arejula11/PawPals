@extends('layouts.app')

@section('content')
<div class="login-part">
    <img src="{{ asset('images/pet_owner_photo.jpg') }}" alt="Pet_owner" class="login-img img-dog">
    <img src="{{ asset('images/cat_owner_photo.jpg') }}" alt="Cat_owner" class="login-img img-cat">
    <img src="{{ asset('images/parrot_owner_photo.jpg') }}" alt="Parrot_owner" class="login-img img-parrot">
    <img src="{{ asset('images/bird_owner_photo.jpg') }}" alt="Bird_owner" class="login-img img-bird">
    <div class="login-container" style="background-image: url('{{ asset('images/paws_photo.jpg') }}');">
        
        <form method="POST" action="{{ route('login') }}" class="login-form">

            {{ csrf_field() }}

            <label for="email">E-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Your e-mail" required autofocus>
            <span id="email-error" class="error" style="display: none;"></span>
            @if ($errors->has('email'))
                <span class="error">
                    {{ $errors->first('email') }}
                </span>
            @endif

            <label for="password">Password</label>
            <input id="password" type="password" name="password" placeholder="Your password" required>
            <span id="password-error" class="error" style="display: none;"></span>
            @if ($errors->has('password'))
                <span class="error">
                    {{ $errors->first('password') }}
                </span>
            @endif

            <label>
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
            </label>

            <button type="submit">Login</button>

            <a class="button button-outline" href="{{ route('register') }}">Register</a>

            <a class="" href="{{ route('password') }}">Forgotten your password?</a>

            @if (session('success'))
                <p class="success">
                    {{ session('success') }}
                </p>
            @endif
        </form>
    </div>
</div>

@endsection

@section('scripts')

<script>
    // Fields to validate
    const fields = ['email', 'password'];

    // Real-time validation function
    function validateField(field) {
        const input = document.getElementById(field);
        const errorElement = document.getElementById(`${field}-error`);

        // Email validation
        if (field === 'email') {
            if (input.value.trim() === '') {
                errorElement.textContent = 'Email is required.';
                errorElement.style.display = 'block';
                errorElement.style.color = 'red';
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value)) {
                errorElement.textContent = 'Please enter a valid email address.';
                errorElement.style.display = 'block';
                errorElement.style.color = 'red';
            } else {
                errorElement.textContent = '';
                errorElement.style.display = 'none';
            }
        }

        // Password validation
        else if (field === 'password') {
            if (input.value.trim() === '') {
                errorElement.textContent = 'Password is required.';
                errorElement.style.display = 'block';
                errorElement.style.color = 'red';
            } else if (input.value.trim().length < 8) {
                errorElement.textContent = 'Password must be at least 8 characters long.';
                errorElement.style.display = 'block';
                errorElement.style.color = 'red';
            } else {
                errorElement.textContent = '';
                errorElement.style.display = 'none';
            }
        }
    }

    // Attach blur event listeners to fields
    fields.forEach(function (field) {
        document.getElementById(field).addEventListener("blur", function () {
            validateField(field);
        });
    });
</script>

@endsection
