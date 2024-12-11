@extends('layouts.app')

@section('content')
<h2>Contact us!</h2>
<p>Do you need help navigating PawPawls or do you have feedback for us? Send us a message and we will reply as soon as possible!</p>
<div class="contact">
    <form method="POST" action="{{ route('static.contact.send') }}" class="contact-form" enctype="multipart/form-data">
        {{ csrf_field() }}
        
        <label for="email">E-Mail Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        @if ($errors->has('email'))
            <span class="error">{{ $errors->first('email') }}</span>
        @endif
   
        <label for="topic">Topic</label>
        <input id="topic" type="text" name="topic" value="{{ old('topic') }}" required>

        <label for="message">Message</label>
        <textarea id="message" name="message" style="height:300px">{{ old('message') }}</textarea>
        @if ($errors->has('message'))
            <span class="error">{{ $errors->first('message') }}</span>
        @endif

        <button type="submit">Send message</button>
        
    </form>
</div>

@endsection
