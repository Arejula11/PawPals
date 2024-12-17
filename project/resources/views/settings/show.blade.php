@extends('layouts.app')

@section('head')
    <link href="{{ asset('css/settings.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="settings-container">
        <h1>Settings</h1>
        <div class="bento">
            <!-- Change Password Section -->
            <div class="bento-item">
                <h2>Change Password</h2>
                <form action="{{ route('user.updatePassword', ['id' => Auth::user()->id ]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="old">Old Password</label>
                        <input type="password" class="form-control" id="old" name="old" required>
                    </div>

                    <div class="form-group">
                        <label for="new">New Password</label>
                        <input type="password" class="form-control" id="new" name="new" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Change</button>
                </form>
            </div>

            <!-- Change privacity -->
            <div class="bento-item">
                <h2>Change Profile Privacy</h2>
                <p>Public Profile</p>
                <form action="{{ route('settings.users.public', ['id' => Auth::user()->id ]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <select class="form-control" id="public" name="public">
                        <option value="1" {{ $user->is_public ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ !$user->is_public ? 'selected' : '' }}>No</option>
                    </select>
                    <button type="submit" class="btn btn-primary" style="margin-top: 78px;">Change</button>
                </div>
            </div>

            <!-- Delete Account Section -->
            <div class="bento-item">
                <h2>Delete Account</h2>
                <form action="{{ route('settings.users.delete', ['id' => Auth::user()->id ]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
            
        </div>
    </div>
@endsection
