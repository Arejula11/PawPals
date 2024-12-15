@extends('layouts.app')

@section('content')
<div class="password-reset">
    <img src="{{ asset('images/pet_owner_photo.jpg') }}" alt="" class="login-img img-dog">
    <img src="{{ asset('images/cat_owner_photo.jpg') }}" alt="" class="login-img img-cat">
    <img src="{{ asset('images/parrot_owner_photo.jpg') }}" alt="" class="login-img img-parrot">
    <img src="{{ asset('images/bird_owner_photo.jpg') }}" alt="" class="login-img img-bird">
    <div class="login-container" style="background-image: url('{{ asset('images/paws_photo.jpg') }}');">
        
        <form method="POST" action="{{ route('password.send') }}" class="login-form">
            <p>Have you forgotten your password?<br>
                Just insert your email and we will send you a new password!</p>
            {{ csrf_field() }}
            <label for="email">E-mail Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Your e-mail" required autofocus>
            <span id="email-error" class="error" style="display: none;"></span>
            @if ($errors->has('email'))
                <span class="error">
                    {{ $errors->first('email') }}
                </span>
            @endif
            <p> </p>

            <button type="submit">Send new Password</button>
            @if (session('error'))
                <p class="error">
                    {{ session('error') }}
                </p>
            @endif
            
        </form>
    </div>
</div>
@endsection

@section('scripts')

<script>
    // Fields to validate
    const fields = ['email'];

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
    }

    // Attach blur event listeners to fields
    fields.forEach(function (field) {
        document.getElementById(field).addEventListener("blur", function () {
            validateField(field);
        });
    });
</script>

@endsection

