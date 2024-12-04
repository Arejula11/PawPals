@extends('layouts.admin')
@php
    $user = \App\Models\User::find($ban->user_id);
@endphp
@section('head')
    <!-- Include only the CSS for this view -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="header">
        <a href="{{route('admin.home')}}" class="name">PetPawls</a>
    </div>

    <h1 style="font-size: 32px; margin-bottom: 10px; margin-top: -40px; text-align: center; color: #333; font-weight: semi-bold;">Bans of user: {{ $user->username }} </h1>
    <div class="appeals">
    <div class="appeals-header">Appeals List</div>
    @foreach($ban->appeals as $appeal)
        <div class="appeal">
                <a href="{{ route('admin.appeal.show', $appeal->id) }}">
                    <div class="appeal-details">
                        <p class="appeal-reason">Reason: {{ $appeal->reason }}</p>
                        <p class="appeal-meta">Date: {{ $appeal->date }}</p>
                    </div>
                    <div class="appeal-active {{ $appeal->active ? 'active' : 'inactive' }}">
                        {{ $appeal->active ? 'Active' : 'Inactive' }}
                    </div>
                </a>
            </div>
    @endforeach
    
</div>
@endsection