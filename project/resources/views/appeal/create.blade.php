@extends('layouts.admin')
@section('head')
    <title>Create Appeal</title>
    <link href="{{ asset('css/appeal.css') }}" rel="stylesheet">
@endsection

@section('content')
    <h1> You have been banned, here you can appeal your ban </h1>
    <h2> You were banned for the following reason: </h2>
    @php
        $ban = Auth::user()->getActiveBanId();
    @endphp
    <p> {{ $ban->reason }} </p>
   
    <h2> Please provide a reason for your appeal:</h2>
    <form action="{{ route('appeal.store') }}" method="POST">
        @csrf
        <input type="hidden" name="ban_id" value="{{ $ban->id }}">
        <textarea name="reason" id="reason" cols="30" rows="10"></textarea>
        <button type="submit">Submit</button>
    </form>
    

@endsection