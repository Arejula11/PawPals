@extends('layouts.admin')

@section('head')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="header">
        <a href="{{ route('admin.home') }}" class="name">PetPawls</a>
        <h1>Groups</h1>
    </div>

    <div class="groups-list">
        @foreach($groups as $group)
        <a href="{{ route('admin.groups.show', $group->id) }}" class="group-item">
            <div class="group-item">
                <div class="group-info">
                    <h2 style="font-size:20px; font-weight: bold;">{{ $group->name }}</h2>
                    <p>{{ $group->description }}</p>
                    <p><strong>Owner:</strong> {{ $group->owner->username }}</p>
                    <p><strong>Visibility:</strong> {{ $group->is_public ? 'Public' : 'Private' }}</p>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <div class="pagination">
        {{ $groups->links() }}
    </div>
@endsection
