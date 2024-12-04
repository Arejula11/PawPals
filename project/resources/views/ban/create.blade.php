@extends('layouts.admin')
@section('head')
    <!-- Include only the CSS for this view -->
    <link href="{{ asset('css/ban.css') }}" rel="stylesheet">
@endsection

@section('content')
    <form action="{{ route('admin.users.ban', ['id' => request()->route('id')]) }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="user_id">User ID</label>
            <input type="text" class="form-control" id="user_id" name="user_id" value="{{ request()->route('id') }}" required>
        </div>

        <div class="form-group">
            <label for="reason">Reason</label>
            <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Ban</button>
    </form>

@endsection