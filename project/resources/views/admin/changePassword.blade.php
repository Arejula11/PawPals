@extends('layouts.admin')
@section('head')
    <!-- Include only the CSS for this view -->
    <link href="{{ asset('css/ban.css') }}" rel="stylesheet">
@endsection

@section('content')
    <form action="{{ route('admin.updatePassword', ['id' => Auth::user()->id ]) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="user_id">Old Password</label>
            <input type="password" class="form-control" id="old" name="old" required>
        </div>

        <div class="form-group">
            <label for="reason">New password</label>
            <input type="password" class="form-control" id="new" name="new" required>
        </div>
        <button type="submit" class="btn btn-primary">Change</button>
    </form>

@endsection