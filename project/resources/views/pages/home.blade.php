@extends('layouts.app')

@section('content')

@if (Auth::check())
    <div="container"> Hello, {{ Auth::user()->username }}!</div>
    <img src="{{ Auth::user()->profilePicture() }}">
    <form method="POST" action="/file/upload" enctype="multipart/form-data">
    @csrf
        <input name="file" type="file" required>
        <input name="id" type="number" value="{{ Auth::user()->id }}" hidden>
        <input name="type" type="text" value="profile" hidden>
    <button type="submit">Submit</button>
</form>

@else
    <div="container"> Hello, Stranger!</div>
@endif
@endsection