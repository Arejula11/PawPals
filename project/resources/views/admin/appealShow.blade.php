@extends('layouts.admin')
@php
    $user = \App\Models\User::find($appeal->user_id);
@endphp
@section('head')
    <!-- Include only the CSS for this view -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="header">
        <a href="{{route('admin.home')}}" class="name">PetPawls</a>
    </div>
    <div class="appeal-details">
        <p class="appeal-reason">Reason: {{ $appeal->reason }}</p>
        <p class="appeal-meta">Date: {{ $appeal->date }}</p>
    <form action="{{ route('admin.appeal.update', $appeal->id) }}" method="POST">
        @csrf
        @method('PUT')
        <button type="submit" class="appeal-active {{ $appeal->status ? 'active' : 'inactive' }}">
            {{ $appeal->status ? 'Accepted' : 'Declined' }}
        </button>
    </form>
    
</div>
@endsection