@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Create a New Post</h1>
    
    {{-- Display Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Post Creation Form --}}
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="post_picture" class="form-label">Upload Picture</label>
            <input type="file" id="post_picture" name="post_picture" class="form-control">
        </div>

        <div class="mb-3">  
            <label for="is_public" class="form-label">Is Public:</label>
            <select name="is_public" id="is_public" class="form-select">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
        

        <div class="text-center">
            <button type="submit" class="btn btn-primary" style="width: auto; height: auto; font-size: 0.9rem; padding: 8px 16px;">Create Post</button>
        </div>
    </form>
</div>
@endsection
