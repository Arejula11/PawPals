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
            <label for="email">E-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @if ($errors->has('email'))
                <span class="error">
                    {{ $errors->first('email') }}
                </span>
            @endif
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



