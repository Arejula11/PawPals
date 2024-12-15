@extends('layouts.app')

@section('head')

<link href="{{ asset('css/static.css') }}" rel="stylesheet">

@endsection

@section('content')
<h2>Contact us!</h2>
<p>Do you need help navigating PawPawls, do you have feedback for us or do you want to report inappropriate behaviour of another user?<br>Send us a message and we will reply as soon as possible!</p>
<div class="contact">
    <form method="POST" action="{{ route('static.contact.send') }}" class="contact-form" enctype="multipart/form-data">
        {{ csrf_field() }}
        
        <label for="email">E-Mail Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Your e-mail" required>
        <span id="email-error" class="error" style="display: none;"></span>
   
        <label for="topic">Topic</label>
        <input id="topic" type="text" name="topic" value="{{ old('topic') }}" placeholder="Help/Feedback/Report" required>
        <span id="topic-error" class="error" style="display: none;"></span>

        <label for="message">Message</label>
        <textarea id="message" name="message" type="text" style="height:200px" value="{{ old('message') }}" placeholder="Your detailed request"></textarea>
        <span id="message-error" class="error" style="display: none;"></span>
        <p> </p>

        <button type="submit">Send message</button>
    </form>
</div>

@endsection


@section('scripts')

<script>
    // Fields to validate
    const fields = ['email', 'topic', 'message'];

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
        
        // Topic validation
        else if (field === 'topic') {
            if (input.value.trim() === '') {
                errorElement.textContent = 'Topic is required.';
                errorElement.style.display = 'block';
                errorElement.style.color = 'red';
            } else if (input.value.length > 255) {
                errorElement.textContent = 'Topic cannot be longer than 255 characters.';
                errorElement.style.display = 'block';
                errorElement.style.color = 'red';
            } else {
                errorElement.textContent = '';
                errorElement.style.display = 'none';
            }
        }

        // Message validation
        else if (field === 'message') {
            if (input.value.trim() === '') {
                errorElement.textContent = 'Message is required.';
                errorElement.style.display = 'block';
                errorElement.style.color = 'red';
            } else if (input.value.trim().length < 10) {
                errorElement.textContent = 'Message must be at least 10 characters long.';
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