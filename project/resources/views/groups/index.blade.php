@extends('layouts.app')

@section('content')
<div class="groups-part">
    <div class="actions">
        <a href="{{ route('groups.create') }}" class="button">Create New Group</a>
        <a href="{{ route('groups.search') }}" class="button">Search for Groups</a>
    </div>
    <h2>Your Groups:</h2>
    @if ($userGroups->isEmpty())
        <p>You are not in any group.</p>
    @else
        <ul>
            @foreach($userGroups as $group)
                <li>
                    <a href="{{ route('groups.messages', $group->id) }}">{{ $group->name }}</a>
                    <a href="{{ route('groups.show', $group->id) }}" class="button">
                        <img src="{{ asset('images/settings.png') }}" alt="Settings" class="settings-icon">
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection