@extends('layouts.app')
@section('head')
    <title>Create New Group</title>
@endsection
@section('content')
<div class="create-group-container">
    <h1>Create a New Group</h1>
    <form action="{{ route('groups.store') }}" method="POST" class="create-group-form">
        @csrf
        <label for="name" class="form-label">Name</label>
        <input type="text" name="name" id="name" class="form-input" placeholder="Your group name" required>
        <span id="name-error" class="error" style="display: none;"></span>
        
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" class="form-textarea" placeholder="Your group description" required></textarea>
        <span id="description-error" class="error" style="display: none;"></span>
        <p> </p>

        <!-- For further features -->
        <input type="hidden" name="img_id" id="img_id" value="1">
        <input type="hidden" name="is_public" id="is_public" value="1">
        
        <!-- 
        <select name="is_public" id="is_public" class="form-select" value="1" hidden>
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
        <input type="number" name="img_id" id="img_id" class="form-input" value="1">
        -->

        

        <button type="submit" class="submit-button">Create Group</button>
    </form>
</div>
@endsection


@section('scripts')

<script>
    // Fields to validate
    const fields = ['name', 'description'];

    // Real-time validation function
    function validateField(field) {
        const input = document.getElementById(field);
        const errorElement = document.getElementById(`${field}-error`);

        // Name validation
        if (field === 'name') {
            if (input.value.trim() === '') {
                errorElement.textContent = 'Group name is required.';
                errorElement.style.display = 'block';
                errorElement.style.color = 'red';
            } else if (input.value.length > 255) {
                errorElement.textContent = 'Group name cannot be longer than 255 characters.';
                errorElement.style.display = 'block';
                errorElement.style.color = 'red';
            } else {
                errorElement.textContent = '';
                errorElement.style.display = 'none';
            }
        }
        
        //  validation
        else if (field === 'description') {
            if (input.value.trim() === '') {
                errorElement.textContent = 'Group description is required.';
                errorElement.style.display = 'block';
                errorElement.style.color = 'red';
            } else {
                errorElement.textContent = '';
                errorElement.style.display = 'none';
            }
        }
    }

    // Attach blur event listeners to fields
    fields.forEach(function (field) {
        document.getElementById(field).addEventListener("blur", function () {
            validateField(field);
        });
    });
</script>

@endsection