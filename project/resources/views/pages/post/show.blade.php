@extends('layouts.app')

@section('content')
<div class="container" style="display: flex; justify-content: center; align-items: center; height: 100vh;">
    <!-- Post Card -->
    <div class="card" style="width: 100%; max-width: 600px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); display: flex; flex-direction: column; justify-content: space-between; height: auto;">
        <!-- Post Picture -->
        @if($post->post_picture)
        <div style="padding: 25px 15px 0px 15px;"> <!-- Top padding for image -->
            <img src="{{ asset($post->post_picture) }}" class="card-img-top" alt="Post Picture" style="max-height: 300px; object-fit: cover; width: 100%; border-bottom: 1px solid #d1d1d1;">
        </div>
        @endif

        <!-- Post Description and Details at the Bottom -->
        <div class="card-body" style="margin-top: auto; padding: 20px;">
            <!-- Post Description -->
            <h5 class="card-title" style="margin-bottom: 15px; color: #333;">Post Description</h5>
            <p class="card-text" style="margin-bottom: 20px; color: #606c76;">{{ $post->description }}</p>

            <!-- Post Details -->
            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                <small style="color: #606c76;">Posted by: <strong>{{ $post->user->username }}</strong></small>
                <small style="color: #606c76;">Created on: {{ \Carbon\Carbon::parse($post->creation_date)->format('F j, Y \a\t g:i A') }}</small>
            </div>
        </div>
    </div>
</div>
@endsection



