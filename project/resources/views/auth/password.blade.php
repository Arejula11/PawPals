@extends('layouts.app')

@section('content')

<form method="POST" action="/send">
    @csrf
    <label for="name">Your name</label>
    <input id="name" type="text" name="name" placeholder="Name" required>
    <label for="email">Your email</label>
    <input id="email" type="email" name="email" placeholder="Email" required>
    <button type="submit">Send</button>
</form>

@endsection