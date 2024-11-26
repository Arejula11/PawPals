@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Posts</h1>
    <div class="list-group">
        @foreach ($posts as $post)
        <div class="list-group-item">
            <h5>{{ $post->description }}</h5>
            <p>Posted by: {{ $post->user->username }} on {{ $post->creation_date }}</p>
            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary btn-sm">Edit</a>
            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    {{ $posts->links() }}
</div>
@endsection
