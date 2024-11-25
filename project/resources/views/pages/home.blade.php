@extends('layouts.app')

@section('content')

@if (Auth::check())
    <div="container"> Hello, {{ Auth::user()->username }}!</div>
@else
    <div="container"> Hello, Stranger!</div>
@endif
@endsection