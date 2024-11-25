@extends('layouts.app')

@section('content')
<div class="edit-group">
    <h1>Edit Group</h1>
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
