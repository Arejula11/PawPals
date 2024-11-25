@extends('layouts.app')

@section('content')
<div class="create-group-container">
    <h1>Create a New Group</h1>
    <form action="{{ route('groups.store') }}" method="POST" class="create-group-form">
        @csrf
        <label for="name" class="form-label">Group Name:</label>
        <input type="text" name="name" id="name" class="form-input" required>
        
        <label for="description" class="form-label">Description:</label>
        <textarea name="description" id="description" class="form-textarea" required></textarea>

        <label for="is_public" class="form-label">Is Public:</label>
        <select name="is_public" id="is_public" class="form-select">
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>

        <label for="img_id" class="form-label">Image ID:</label>
        <input type="number" name="img_id" id="img_id" class="form-input" required>

        <button type="submit" class="submit-button">Create Group</button>
    </form>
</div>
@endsection
