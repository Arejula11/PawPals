@extends('layouts.admin')
@section('head')
    <title>Edit User Profile</title>
    <link href="{{ asset('css/editProfile.css') }}" rel="stylesheet">
@endsection

@section('content')
    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $user->firstname }}" required>
        </div>

        <div class="form-group">
            <label for="surname">Surname</label>
            <input type="text" class="form-control" id="surname" name="surname" value="{{ $user->surname }}" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="form-group">
            <label for="profile_picture">Profile Picture</label>
            <input type="file" class="form-control-file" id="profile_picture" name="profile_picture">
        </div>

        <div class="form-group">
            <label for="bio_description">Bio Description</label>
            <textarea class="form-control" id="bio" name="bio_description" rows="3">{{ $user->bio_description }}</textarea>
        </div>

        <div class="form-group">
            <label for="public">Public Profile</label>
            <select class="form-control" id="public" name="public">
                <option value="1" {{ $user->is_public ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ !$user->is_public ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="form-group">
            <label for="type">Type</label>
            <select class="form-control" id="type" name="type">
                <option value="pet owner" {{ $user->type == 'pet owner' ? 'selected' : '' }}>Pet Owner</option>
                <option value="admin" {{ $user->type == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="veterinarian" {{ $user->type == 'veterinarian' ? 'selected' : '' }}>Veterinarian</option>
                <option value="adoption organization" {{ $user->type == 'adoption organization' ? 'selected' : '' }}>Adoption Organization</option>
                <option value="rescue organization" {{ $user->type == 'rescue organization' ? 'selected' : '' }}>Rescue Organization</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection