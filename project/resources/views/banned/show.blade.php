@extends('layouts.admin')
@section('head')
    <link href="{{ asset('css/banned.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="banned-div">
        <div class="banned-content">
            <h1>Your account has been banned</h1>
            <h2>To recover your account you can appeal</h2>
            <a class="link" href="{{(route('appeal.create'))}}" > Appeal my ban </a>
        </div>
    </div>

   
@endsection
