@extends('layouts.app')
@section('head')
    <title>{{ $group->name }}</title>
@endsection

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

        @if($group->participants->contains(auth()->id()) && auth()->id() !== $group->owner_id)
            <button class="button leave-button" onclick="toggleKickConfirm({{auth()->id()}})">Leave Group</button>
            <div id="overlay2" class="overlay-deleting-user "></div>
            <div class="deleting-user-group" id="kick-confirm-{{auth()->id()}}" style="display: none;">
                <p>Do you want to leave the group?</p>
                <div class="button-container">
                    <button class="button-deleting-user-group" onclick="kickUser({{ $group->id }}, {{auth()->id()}})">Yes</button>
                    <button class="button-deleting-user-group" onclick="toggleKickConfirm({{auth()->id()}})">No</button>
                </div>
            </div>
        @endif
    </div>

    <h2>Participants:</h2>
    <div class="participants-section">
        @if($group->participants->isEmpty())
            <p class="no-participants">No participants yet. Be the first to join!</p>
        @else
            @foreach ($group->participants as $participant)
                <div class="participant-item">
                    @if (auth()->id() === $group->owner_id && $participant->id !== $group->owner_id)
                        <p class="participant-name">{{ $participant->username }}</p>
                        <button class="confirm-deleting-user-group" onclick="toggleKickConfirm({{ $participant->id }})">Kick</button>
                        <div id="overlay2" class="overlay-deleting-user "></div>
                        <div class="deleting-user-group" id="kick-confirm-{{ $participant->id }}" style="display: none;">
                            <p>Do you want to kick {{$participant->username}} from the group?</p>
                            <div class="button-container">
                                <button class="button-deleting-user-group" onclick="kickUser({{ $group->id }}, {{ $participant->id }})">Yes</button>
                                <button class="button-deleting-user-group" onclick="toggleKickConfirm({{ $participant->id }})">No</button>
                            </div>
                        </div>
                    @elseif (auth()->id() === $group->owner_id && $participant->id === $group->owner_id)
                        <p class="owner-name">{{ $participant->username }}</p>
                        <img class="crown-owner" src="{{ asset('images/crown.png') }}" alt="Group Owner">
                    @elseif (auth()->id() !== $group->owner_id && $participant->id === $group->owner_id)
                        <p class="owner-name">{{ $participant->username }}</p>
                        <img class="crown-owner" src="{{ asset('images/crown.png') }}" alt="Group Owner">
                    @else
                        <p class="participant-name">{{ $participant->username }}</p>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</div>
<script>
    function toggleKickConfirm(userId) {
        const confirmBox = document.getElementById(`kick-confirm-${userId}`);
        const overlay = document.getElementById("overlay2");

        const isHidden = confirmBox.style.display === "none";

        confirmBox.style.display = isHidden ? "block" : "none";
        overlay.style.display = isHidden ? "block" : "none";
        overlay.style.background = isHidden ? "rgba(0, 0, 0, 0.5)" : "rgba(0, 0, 0, 0)";
    }


    async function kickUser(groupId, userId) {
        const response = await fetch(`/groups/${groupId}/participants/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            location.reload();
        } else {
            alert('Failed to remove user');
        }
    }
</script>
@endsection
