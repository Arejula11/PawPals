@extends('layouts.admin')
@section('head')
    <!-- Include only the CSS for this view -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="header">
        <a href="{{route('admin.home')}}" class="name">PetPawls</a>
    </div>

    <h1 style="font-size: 32px; margin-bottom: 10px; margin-top: -40px; text-align: center; color: #333; font-weight: semi-bold;">Edit Group</h1>
    <form action="{{ route('groups.update', $group->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div>
            <label for="name">Group Name:</label>
            <input type="text" name="name" id="name" value="{{ $group->name }}" required>
        </div>

        <div>
            <label for="description">Description:</label>
            <textarea name="description" id="description" required>{{ $group->description }}</textarea>
        </div>

        <button type="submit" class="button">Save Changes</button>
    </form>
</div>
@endsection

    