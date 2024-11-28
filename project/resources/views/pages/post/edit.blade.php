@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Post</h1>
    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" required>{{ old('description', $post->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="post_picture" class="form-label">Post Picture</label>
            @if ($post->post_picture)
            <div>
                <img src="{{ asset($post->getPostPicture()) }}" alt="Post Picture" style="max-height: 200px;">
            </div>
            @endif
            <input type="file" name="post_picture" id="post_picture" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
