@extends('layouts.app')

@section('content')
<div class="login-part">
    <img src="{{ asset('images/pet_owner_photo.jpg') }}" alt="" class="login-img img-dog">
    <img src="{{ asset('images/cat_owner_photo.jpg') }}" alt="" class="login-img img-cat">
    <img src="{{ asset('images/parrot_owner_photo.jpg') }}" alt="" class="login-img img-parrot">
    <img src="{{ asset('images/bird_owner_photo.jpg') }}" alt="" class="login-img img-bird">
    <div class="login-container" style="background-image: url('{{ asset('images/paws_photo.jpg') }}');">
        
        <form method="POST" action="{{ route('login') }}" class="login-form">

            {{ csrf_field() }}

            <label for="email">E-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @if ($errors->has('email'))
                <span class="error">
                    {{ $errors->first('email') }}
                </span>
            @endif

            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
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
