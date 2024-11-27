@extends('layouts.app')

@section('content')
<div class="register-part">
    <img src="{{ asset('images/pet_owner_photo.jpg') }}" alt="" class="register-img img-dog">
    <img src="{{ asset('images/cat_owner_photo.jpg') }}" alt="" class="register-img img-cat">
    <img src="{{ asset('images/parrot_owner_photo.jpg') }}" alt="" class="register-img img-parrot">
    <img src="{{ asset('images/bird_owner_photo.jpg') }}" alt="" class="register-img img-bird">
    <div class="register-container" style="background-image: url('{{ asset('images/paws_photo.jpg') }}');">
        <form method="POST" action="{{ route('register') }}" class="register-form" enctype="multipart/form-data">
            {{ csrf_field() }}
            
            <label for="username">Username</label>
            <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus>
            @if ($errors->has('username'))
                <span class="error">{{ $errors->first('username') }}</span>
            @endif

            <label for="firstname">First Name</label>
            <input id="firstname" type="text" name="firstname" value="{{ old('firstname') }}" required>
            @if ($errors->has('firstname'))
                <span class="error">{{ $errors->first('firstname') }}</span>
            @endif

            <label for="surname">Surname</label>
            <input id="surname" type="text" name="surname" value="{{ old('surname') }}" required>
            @if ($errors->has('surname'))
                <span class="error">{{ $errors->first('surname') }}</span>
            @endif

            <label for="email">E-Mail Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            @if ($errors->has('email'))
                <span class="error">{{ $errors->first('email') }}</span>
            @endif

            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
            @if ($errors->has('password'))
                <span class="error">{{ $errors->first('password') }}</span>
            @endif

            <label for="password-confirm">Confirm Password</label>
            <input id="password-confirm" type="password" name="password_confirmation" required>

            <label for="bio_description">Bio Description</label>
            <textarea id="bio_description" name="bio_description">{{ old('bio_description') }}</textarea>
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
