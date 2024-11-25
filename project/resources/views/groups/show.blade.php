@extends('layouts.app')

@section('content')
<div class="group-details">
    <div class="group-header">
        <h1>{{ $group->name }}</h1>
        <p class="description">{{ $group->description }}</p>
    </div>

    <div class="group-actions">
        @if(auth()->id() === $group->owner_id)
            <a href="{{ route('groups.edit', $group->id) }}" class="button edit-button">Edit Group</a>
        @endif

        @if(!$group->participants->contains(auth()->id()))
            <form action="{{ route('groups.join', $group->id) }}" method="POST" class="join-form">
                @csrf
                <button type="submit" class="button join-button">Join Group</button>
            </form>
        @endif
    </div>

    <h2>Participants:</h2>
    <div class="participants-section">
        @if($group->participants->isEmpty())
            <p class="no-participants">No participants yet. Be the first to join!</p>
        @else
            <ul class="participants-list">
                @foreach($group->participants as $participant)
                    <li class="participant-item">{{ $participant->username }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
