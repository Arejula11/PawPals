@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}

    <!-- Username -->
    <label for="username">Username</label>
    <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus>
    @if ($errors->has('username'))
        <span class="error">{{ $errors->first('username') }}</span>
    @endif

    <!-- First Name -->
    <label for="firstname">First Name</label>
    <input id="firstname" type="text" name="firstname" value="{{ old('firstname') }}" required>
    @if ($errors->has('firstname'))
        <span class="error">{{ $errors->first('firstname') }}</span>
    @endif

    <!-- Surname -->
    <label for="surname">Surname</label>
    <input id="surname" type="text" name="surname" value="{{ old('surname') }}" required>
    @if ($errors->has('surname'))
        <span class="error">{{ $errors->first('surname') }}</span>
    @endif

    <!-- E-Mail -->
    <label for="email">E-Mail Address</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
    @if ($errors->has('email'))
        <span class="error">{{ $errors->first('email') }}</span>
    @endif

    <!-- Password -->
    <label for="password">Password</label>
    <input id="password" type="password" name="password" required>
    @if ($errors->has('password'))
        <span class="error">{{ $errors->first('password') }}</span>
    @endif

    <!-- Confirm Password -->
    <label for="password-confirm">Confirm Password</label>
    <input id="password-confirm" type="password" name="password_confirmation" required>

    <!-- Bio Description -->
    <label for="bio_description">Bio Description</label>
    <textarea id="bio_description" name="bio_description">{{ old('bio_description') }}</textarea>
    @if ($errors->has('bio_description'))
        <span class="error">{{ $errors->first('bio_description') }}</span>
    @endif

    <!-- Profile Type -->
    <label for="type">Profile Type</label>
    <select id="type" name="type" required>
        <option value="">Select your profile type</option>
        <option value="pet owner" {{ old('type') == 'pet owner' ? 'selected' : '' }}>Pet Owner</option>
        <option value="admin" {{ old('type') == 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="veterinarian" {{ old('type') == 'veterinarian' ? 'selected' : '' }}>Veterinarian</option>
        <option value="adoption organization" {{ old('type') == 'adoption organization' ? 'selected' : '' }}>Adoption Organization</option>
        <option value="rescue organization" {{ old('type') == 'rescue organization' ? 'selected' : '' }}>Rescue Organization</option>
    </select>
    @if ($errors->has('type'))
        <span class="error">{{ $errors->first('type') }}</span>
    @endif

    <!-- Is Public -->
    <label for="is_public">
        <input id="is_public" type="checkbox" name="is_public" {{ old('is_public') ? 'checked' : '' }}>
        Make Profile Public
    </label>

    <!-- Profile Picture -->
    <label for="profile_picture">Profile Picture ID</label>
    <input id="profile_picture" type="number" name="profile_picture" value="{{ old('profile_picture') }}" required>
    @if ($errors->has('profile_picture'))
        <span class="error">{{ $errors->first('profile_picture') }}</span>
    @endif

    <button type="submit">Register</button>
    <a class="button button-outline" href="{{ route('login') }}">Login</a>
</form>
@endsection
